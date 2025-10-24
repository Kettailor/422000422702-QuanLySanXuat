export interface WorkOrderSummary {
  orderCode: string;
  lineCode: string;
  lineName?: string;
  department?: string;
  productCode: string;
  plannedQuantity: number;
  completedQuantity: number;
  scrapQuantity: number;
  status: string;
  dueTime?: string;
  createdAt?: string;
}

export interface ProductionLineSummary {
  lineCode: string;
  lineName?: string;
  department?: string;
  status: string;
  plannedOutput: number;
  actualOutput: number;
  downtimeMinutes: number;
  activeWorkOrders: number;
}

export interface ShiftPerformance {
  shiftDate: string;
  shiftName: string;
  plannedOutput: number;
  actualOutput: number;
  downtimeMinutes: number;
}

export interface WorkOrderEvent {
  type: string;
  payload?: Record<string, unknown> | null;
  metadata?: {
    source?: string;
    occurredAt?: string;
  } | null;
  receivedAt?: string;
}

export interface SummaryResponse {
  workOrders: WorkOrderSummary[];
  productionLines: ProductionLineSummary[];
  dailyPerformance: ShiftPerformance[];
  recentEvents: WorkOrderEvent[];
}

export class UnauthorizedError extends Error {
  constructor(message = 'Phiên đăng nhập đã hết hạn') {
    super(message);
    this.name = 'UnauthorizedError';
  }
}

export interface AuthUser {
  username: string;
  role?: string;
}

const DEFAULT_API_BASE = 'http://localhost:3000';
const API_BASE_URL = (import.meta.env.VITE_API_BASE_URL ?? DEFAULT_API_BASE).replace(/\/$/, '');

const createUrl = (path: string) => `${API_BASE_URL}${path}`;

const wrapNetworkError = async <T>(promise: Promise<T>): Promise<T> => {
  try {
    return await promise;
  } catch (error) {
    if (error instanceof TypeError) {
      throw new Error('Không thể kết nối tới máy chủ. Vui lòng thử lại sau.');
    }
    throw error;
  }
};

interface LoginResponse {
  token?: string;
  user?: AuthUser;
  message?: string;
}

const handleResponse = async <T>(response: Response, fallbackMessage: string): Promise<T> => {
  if (response.status === 401) {
    throw new UnauthorizedError();
  }

  if (!response.ok) {
    throw new Error(fallbackMessage);
  }

  const data = (await response.json()) as T;
  return data;
};

export const login = async ({
  username,
  password,
}: {
  username: string;
  password: string;
}): Promise<{ token: string; user: AuthUser | null }> => {
  const response = await wrapNetworkError(
    fetch(createUrl(`/auth/login`), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({ username, password }),
    })
  );

  const data = await handleResponse<LoginResponse>(response, 'Không thể đăng nhập');
  if (!data.token) {
    throw new Error(data.message || 'Máy chủ không trả về mã xác thực');
  }

  return { token: data.token, user: data.user ?? null };
};

export const logout = async (token: string): Promise<void> => {
  const response = await wrapNetworkError(
    fetch(createUrl(`/auth/logout`), {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
      },
      credentials: 'include',
    })
  );

  if (!response.ok) {
    throw new Error('Không thể đăng xuất');
  }
};

export const getSession = async (token: string): Promise<{ token: string; user: AuthUser | null }> => {
  const response = await wrapNetworkError(
    fetch(createUrl(`/auth/session`), {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      credentials: 'include',
    })
  );

  const data = await handleResponse<LoginResponse>(response, 'Không thể khôi phục phiên đăng nhập');
  if (!data.token) {
    throw new UnauthorizedError();
  }

  return { token: data.token, user: data.user ?? null };
};

export const fetchSummaryReport = async (token: string): Promise<SummaryResponse> => {
  const response = await wrapNetworkError(
    fetch(createUrl(`/api/reports/summary`), {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      credentials: 'include',
    })
  );

  return handleResponse<SummaryResponse>(response, 'Không thể tải báo cáo tổng quan');
};

export const apiConfig = {
  baseUrl: API_BASE_URL,
};
