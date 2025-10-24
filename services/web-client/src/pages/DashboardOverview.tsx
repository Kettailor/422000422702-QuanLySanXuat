import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { SummaryResponse, WorkOrderEvent, fetchSummaryReport, UnauthorizedError } from '../api/client';
import { useAuth } from '../context/AuthContext';

interface StatCardData {
  label: string;
  value: string;
  trend: string;
  accent: string;
}

interface ActivityRow {
  code: string;
  product: string;
  line: string;
  dueTime?: string;
  status: string;
  progress: number;
  completed: number;
  planned: number;
}

interface AnnouncementItem {
  message: string;
  time?: string | null;
}

interface TimelineItem {
  key: string;
  title: string;
  time: string;
  subtitle: string;
  highlight: string;
  statusKey: string;
  statusLabel: string;
  timestamp: number;
}

interface LineHighlight {
  code: string;
  name: string;
  statusKey: string;
  statusLabel: string;
  efficiency: string;
  efficiencyValue: number;
  downtime: string;
  activeOrders: number;
}

const placeholderStats: (StatCardData | null)[] = [null, null, null, null];

export default function DashboardOverview() {
  const { token, handleUnauthorized } = useAuth();
  const [summary, setSummary] = useState<SummaryResponse | null>(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const isMounted = useRef(true);

  useEffect(() => () => {
    isMounted.current = false;
  }, []);

  const loadSummary = useCallback(
    async (options?: { silent?: boolean }) => {
      if (!token) {
        return;
      }

      const silent = options?.silent ?? false;
      if (isMounted.current) {
        setError(null);
        if (silent) {
          setRefreshing(true);
        } else {
          setLoading(true);
        }
      }

      try {
        const report = await fetchSummaryReport(token);
        if (isMounted.current) {
          setSummary(report);
        }
      } catch (loadError) {
        if (loadError instanceof UnauthorizedError) {
          if (isMounted.current) {
            setError('Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
          }
          await handleUnauthorized();
          return;
        }

        if (isMounted.current) {
          setSummary(null);
          setError((loadError as Error).message || 'Không thể tải dữ liệu tổng quan');
        }
      } finally {
        if (isMounted.current) {
          if (silent) {
            setRefreshing(false);
          } else {
            setLoading(false);
          }
        }
      }
    },
    [handleUnauthorized, token]
  );

  useEffect(() => {
    if (!token) {
      return;
    }
    loadSummary();
  }, [loadSummary, token]);

  const stats = useMemo<StatCardData[] | null>(() => {
    if (!summary) {
      return null;
    }

    const totalOrders = summary.workOrders.length;
    const inProgress = summary.workOrders.filter(
      (order) => normalizeStatus(order.status) === 'in_progress'
    ).length;
    const completed = summary.workOrders.filter(
      (order) => normalizeStatus(order.status) === 'completed'
    ).length;
    const planned = summary.workOrders.filter(
      (order) => normalizeStatus(order.status) === 'planned'
    ).length;
    const activeLines = summary.productionLines.length;

    return [
      {
        label: 'Tổng lệnh sản xuất',
        value: formatNumber(totalOrders),
        trend: `${activeLines} chuyền đang tham gia kế hoạch`,
        accent: '#2563eb',
      },
      {
        label: 'Đang thực hiện',
        value: formatNumber(inProgress),
        trend: `${planned} lệnh chờ khởi chạy`,
        accent: '#f59e0b',
      },
      {
        label: 'Hoàn thành',
        value: formatNumber(completed),
        trend: `${percentage(completed, totalOrders)}% tổng lệnh tuần này`,
        accent: '#16a34a',
      },
      {
        label: 'Thông báo mới',
        value: formatNumber(summary.recentEvents.length),
        trend: 'Nhật ký vận hành 24 giờ qua',
        accent: '#6366f1',
      },
    ];
  }, [summary]);

  const activities = useMemo<ActivityRow[]>(() => {
    if (!summary) {
      return [];
    }

    return [...summary.workOrders]
      .sort((a, b) => {
        const aTime = toDate(a.dueTime)?.getTime() ?? Number.MAX_SAFE_INTEGER;
        const bTime = toDate(b.dueTime)?.getTime() ?? Number.MAX_SAFE_INTEGER;
        return aTime - bTime;
      })
      .slice(0, 6)
      .map((order) => {
        const status = normalizeStatus(order.status);
        return {
          code: order.orderCode,
          product: order.productCode,
          line: order.lineName ?? order.lineCode,
          dueTime: order.dueTime,
          status,
          progress: percentage(order.completedQuantity, order.plannedQuantity),
          completed: order.completedQuantity,
          planned: order.plannedQuantity,
        };
      });
  }, [summary]);

  const announcements = useMemo<AnnouncementItem[]>(() => {
    if (!summary) {
      return [];
    }

    const fromEvents = summary.recentEvents
      .map((event) => {
        const message = describeEvent(event);
        if (!message) {
          return null;
        }
        return {
          message,
          time: formatRelativeTime(event.metadata?.occurredAt ?? event.receivedAt ?? null),
        };
      })
      .filter((item): item is AnnouncementItem => Boolean(item))
      .slice(0, 4);

    if (fromEvents.length > 0) {
      return fromEvents;
    }

    const specialLines = summary.productionLines.filter(
      (line) => normalizeStatus(line.status) !== 'active'
    );
    if (specialLines.length > 0) {
      return specialLines.map((line) => ({
        message: `${line.lineName ?? line.lineCode} đang ở trạng thái ${getStatusLabel(
          normalizeStatus(line.status)
        )}`,
        time:
          line.downtimeMinutes > 0
            ? `${formatNumber(line.downtimeMinutes)} phút downtime`
            : 'Đang xử lý',
      }));
    }

    return summary.workOrders
      .filter((order) => normalizeStatus(order.status) !== 'completed')
      .slice(0, 3)
      .map((order) => ({
        message: `Đơn ${order.orderCode} cần theo dõi (${getStatusLabel(
          normalizeStatus(order.status)
        )})`,
        time: order.dueTime ? `Kế hoạch: ${formatDate(order.dueTime)}` : null,
      }));
  }, [summary]);

  const timeline = useMemo<TimelineItem[]>(() => {
    if (!summary) {
      return [];
    }

    return summary.dailyPerformance
      .map((shift) => {
        const completion = percentage(shift.actualOutput, shift.plannedOutput);
        let statusKey = 'in_progress';
        let statusLabel = 'Đang vận hành';

        if (completion >= 95 && shift.downtimeMinutes <= 30) {
          statusKey = 'completed';
          statusLabel = 'Hoàn thành đúng kế hoạch';
        } else if (completion < 70 || shift.downtimeMinutes > 45) {
          statusKey = 'delayed';
          statusLabel = 'Cần hỗ trợ';
        }

        const shiftDate = toDate(shift.shiftDate);
        const timestamp = shiftDate ? shiftDate.getTime() : Date.now();

        return {
          key: `${shift.shiftDate}-${shift.shiftName}`,
          title: shift.shiftName,
          time: formatShiftDate(shift.shiftDate),
          subtitle: `${formatNumber(shift.actualOutput)} / ${formatNumber(
            shift.plannedOutput
          )} sản lượng • Downtime ${formatNumber(shift.downtimeMinutes)} phút`,
          highlight: `${completion}%`,
          statusKey,
          statusLabel,
          timestamp,
        };
      })
      .sort((a, b) => b.timestamp - a.timestamp)
      .slice(0, 6);
  }, [summary]);

  const lineHighlights = useMemo<LineHighlight[]>(() => {
    if (!summary) {
      return [];
    }

    return summary.productionLines
      .map((line) => {
        const efficiency = percentage(line.actualOutput, line.plannedOutput);
        const statusKey = normalizeStatus(line.status);
        return {
          code: line.lineCode,
          name: line.lineName ?? line.lineCode,
          statusKey,
          statusLabel: getStatusLabel(statusKey),
          efficiency: `${efficiency}%`,
          efficiencyValue: efficiency,
          downtime:
            line.downtimeMinutes > 0
              ? `${formatNumber(line.downtimeMinutes)} phút`
              : 'Ổn định',
          activeOrders: line.activeWorkOrders ?? 0,
        };
      })
      .sort((a, b) => b.efficiencyValue - a.efficiencyValue)
      .slice(0, 4);
  }, [summary]);

  const handleRefresh = () => {
    loadSummary({ silent: true });
  };

  return (
    <div className="page dashboard-page">
      <div className="dashboard-header">
        <div>
          <div className="section-title" style={{ marginBottom: 4 }}>Tổng quan điều hành</div>
          <p className="muted-text">Cập nhật tình hình sản xuất và chất lượng theo thời gian thực</p>
        </div>
        <button
          className="button-secondary"
          onClick={handleRefresh}
          disabled={loading || refreshing}
        >
          {refreshing ? 'Đang cập nhật...' : 'Làm mới dữ liệu'}
        </button>
      </div>

      <div className="dashboard-stats">
        {(stats ?? placeholderStats).map((stat, index) => (
          <div key={stat?.label ?? `placeholder-${index}`} className={`stats-card${stat ? '' : ' stats-card--loading'}`}>
            <span
              className="stats-card__accent"
              style={{ background: stat?.accent ?? 'linear-gradient(90deg, #cbd5f5, #e2e8f0)' }}
            />
            {stat ? (
              <>
                <div className="stats-card__label">{stat.label}</div>
                <div className="stats-card__value">{stat.value}</div>
                <div className="stats-card__trend">{stat.trend}</div>
              </>
            ) : (
              <div className="stats-card__skeleton">
                <span />
                <span />
                <span />
              </div>
            )}
          </div>
        ))}
      </div>

      {error ? (
        <div className="section-card error-card">
          <div className="section-title">Không thể tải dữ liệu</div>
          <p className="muted-text" style={{ maxWidth: 420 }}>{error}</p>
          <div className="flex-end">
            <button className="button-primary" onClick={() => loadSummary()} disabled={loading}>
              Thử lại
            </button>
          </div>
        </div>
      ) : (
        <div className="dashboard-content">
          <div className="dashboard-content__main">
            <div className="section-card">
              <div className="section-card__header">
                <div>
                  <div className="section-title">Hoạt động trong tháng</div>
                  <p className="muted-text">Các lệnh sản xuất đang được theo dõi tiến độ</p>
                </div>
              </div>
              <div className="table-wrapper">
                <table className="table">
                  <thead>
                    <tr>
                      <th>Lệnh sản xuất</th>
                      <th>Chuyền phụ trách</th>
                      <th>Kế hoạch</th>
                      <th>Tiến độ</th>
                      <th>Trạng thái</th>
                    </tr>
                  </thead>
                  <tbody>
                    {activities.length === 0 ? (
                      <tr>
                        <td colSpan={5} style={{ textAlign: 'center', padding: '28px 0' }}>
                          <span className="muted-text">
                            {loading ? 'Đang tải dữ liệu...' : 'Chưa có lệnh sản xuất nào trong hệ thống'}
                          </span>
                        </td>
                      </tr>
                    ) : (
                      activities.map((activity) => (
                        <tr key={activity.code}>
                          <td>
                            <div className="table-title">{activity.code}</div>
                            <div className="muted-text">{activity.product}</div>
                          </td>
                          <td>
                            <div className="table-title">{activity.line}</div>
                            {activity.dueTime && <div className="muted-text">Hạn: {formatDate(activity.dueTime)}</div>}
                          </td>
                          <td>
                            {activity.dueTime ? (
                              <>
                                <div className="table-title">{formatDate(activity.dueTime)}</div>
                                <div className="muted-text">{formatTime(activity.dueTime)}</div>
                              </>
                            ) : (
                              <span className="muted-text">Chưa có lịch</span>
                            )}
                          </td>
                          <td>
                            <div className="progress-inline">
                              <div className="progress-inline__bar">
                                <span style={{ width: `${clamp(activity.progress)}%` }} />
                              </div>
                              <div className="progress-inline__label">
                                {formatNumber(activity.completed)} / {formatNumber(activity.planned)}
                              </div>
                            </div>
                          </td>
                          <td>
                            <span className={`status-pill ${getStatusClass(activity.status)}`}>
                              {getStatusLabel(activity.status)}
                            </span>
                          </td>
                        </tr>
                      ))
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            <div className="section-card">
              <div className="section-card__header">
                <div>
                  <div className="section-title">Thông báo quan trọng</div>
                  <p className="muted-text">Bản tin từ hệ thống sản xuất và kiểm soát chất lượng</p>
                </div>
              </div>
              <ul className="announcement-list">
                {announcements.length === 0 ? (
                  <li className="announcement-item muted-text">Không có thông báo nào trong 24 giờ gần nhất</li>
                ) : (
                  announcements.map((item, index) => (
                    <li key={`${item.message}-${index}`} className="announcement-item">
                      <div className="announcement-item__icon">🔔</div>
                      <div>
                        <div className="announcement-item__message">{item.message}</div>
                        {item.time && <div className="announcement-item__time">{item.time}</div>}
                      </div>
                    </li>
                  ))
                )}
              </ul>
            </div>
          </div>

          <div className="dashboard-content__aside">
            <div className="section-card">
              <div className="section-card__header">
                <div className="section-title">Lịch làm việc</div>
                <p className="muted-text">Tổng hợp trạng thái ca sản xuất 3 ngày gần nhất</p>
              </div>
              <div className="dashboard-timeline">
                {timeline.length === 0 ? (
                  <div className="muted-text">Chưa có dữ liệu ca làm việc</div>
                ) : (
                  timeline.map((item) => (
                    <div key={item.key} className="timeline-item">
                      <div className="timeline-item__indicator" />
                      <div className="timeline-item__content">
                        <div className="timeline-item__header">
                          <div>
                            <div className="timeline-item__title">{item.title}</div>
                            <div className="timeline-item__time">{item.time}</div>
                          </div>
                          <div className="timeline-item__highlight">{item.highlight}</div>
                        </div>
                        <div className="timeline-item__subtitle">{item.subtitle}</div>
                        <span className={`status-pill ${getStatusClass(item.statusKey)}`}>
                          {item.statusLabel}
                        </span>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </div>

            <div className="section-card">
              <div className="section-card__header">
                <div className="section-title">Hiệu suất chuyền</div>
                <p className="muted-text">Các chuyền tiêu biểu trong ngày</p>
              </div>
              <div className="line-highlight-list">
                {lineHighlights.length === 0 ? (
                  <div className="muted-text">Chưa có dữ liệu chuyền sản xuất</div>
                ) : (
                  lineHighlights.map((line) => (
                    <div key={line.code} className="line-highlight">
                      <div className="line-highlight__header">
                        <div>
                          <div className="line-highlight__title">{line.name}</div>
                          <div className="muted-text">Mã: {line.code}</div>
                        </div>
                        <span className={`status-pill ${getStatusClass(line.statusKey)}`}>
                          {line.statusLabel}
                        </span>
                      </div>
                      <div className="line-highlight__metrics">
                        <div>
                          <div className="line-highlight__metric">{line.efficiency}</div>
                          <div className="muted-text">Hiệu suất</div>
                        </div>
                        <div>
                          <div className="line-highlight__metric">{line.downtime}</div>
                          <div className="muted-text">Downtime</div>
                        </div>
                        <div>
                          <div className="line-highlight__metric">{formatNumber(line.activeOrders)}</div>
                          <div className="muted-text">Lệnh mở</div>
                        </div>
                      </div>
                    </div>
                  ))
                )}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

function formatNumber(value: number | null | undefined): string {
  if (value === null || value === undefined || Number.isNaN(value)) {
    return '0';
  }
  return value.toLocaleString('vi-VN');
}

function percentage(part: number, total: number): number {
  if (!total) {
    return 0;
  }
  return Math.min(100, Math.max(0, Math.round((part / total) * 100)));
}

function clamp(value: number): number {
  if (Number.isNaN(value)) {
    return 0;
  }
  return Math.min(100, Math.max(0, value));
}

function normalizeStatus(status?: string | null): string {
  if (!status) {
    return 'unknown';
  }
  return status.trim().toLowerCase().replace(/\s+/g, '_');
}

function getStatusLabel(status: string): string {
  switch (status) {
    case 'completed':
      return 'Hoàn thành';
    case 'in_progress':
      return 'Đang thực hiện';
    case 'planned':
      return 'Đã lên kế hoạch';
    case 'maintenance':
      return 'Bảo trì';
    case 'delayed':
      return 'Cần hỗ trợ';
    case 'halted':
      return 'Tạm dừng';
    default:
      return status.replace(/_/g, ' ');
  }
}

function getStatusClass(status: string): string {
  switch (status) {
    case 'completed':
      return 'status-pill--success';
    case 'in_progress':
      return 'status-pill--info';
    case 'planned':
    case 'maintenance':
      return 'status-pill--warning';
    case 'delayed':
    case 'halted':
      return 'status-pill--danger';
    default:
      return 'status-pill--muted';
  }
}

function formatDate(value?: string | null): string {
  const date = toDate(value);
  if (!date) {
    return '---';
  }
  return new Intl.DateTimeFormat('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  }).format(date);
}

function formatTime(value?: string | null): string {
  const date = toDate(value);
  if (!date) {
    return '--:--';
  }
  return new Intl.DateTimeFormat('vi-VN', {
    hour: '2-digit',
    minute: '2-digit',
  }).format(date);
}

function formatShiftDate(value?: string | null): string {
  const date = toDate(value);
  if (!date) {
    return '';
  }
  return new Intl.DateTimeFormat('vi-VN', {
    weekday: 'short',
    day: '2-digit',
    month: '2-digit',
  }).format(date);
}

function describeEvent(event: WorkOrderEvent): string | null {
  if (!event?.type) {
    return null;
  }

  const payload = event.payload ?? {};
  const orderCode = typeof payload?.orderCode === 'string' ? payload.orderCode : undefined;
  const lineName = typeof payload?.lineName === 'string' ? payload.lineName : undefined;
  const status = typeof payload?.status === 'string' ? normalizeStatus(payload.status) : undefined;

  if (event.type === 'work-order.created' && orderCode) {
    return `Tạo lệnh ${orderCode} cho ${lineName ?? 'chuyền sản xuất'}`;
  }

  if (event.type === 'work-order.updated' && orderCode && status) {
    return `Cập nhật ${orderCode}: ${getStatusLabel(status)}`;
  }

  if (event.type === 'quality.alert' && orderCode) {
    return `Cảnh báo chất lượng cho lệnh ${orderCode}`;
  }

  return typeof event.type === 'string' ? event.type : null;
}

function formatRelativeTime(value: string | null): string | null {
  const date = toDate(value);
  if (!date) {
    return null;
  }
  const now = Date.now();
  const diffMs = now - date.getTime();
  const diffMinutes = Math.round(diffMs / 60000);

  if (diffMinutes < 1) {
    return 'Vừa xong';
  }
  if (diffMinutes < 60) {
    return `${diffMinutes} phút trước`;
  }
  const diffHours = Math.round(diffMinutes / 60);
  if (diffHours < 24) {
    return `${diffHours} giờ trước`;
  }
  return `${formatTime(value)} • ${formatDate(value)}`;
}

function toDate(value?: string | null): Date | null {
  if (!value) {
    return null;
  }
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) {
    return null;
  }
  return date;
}
