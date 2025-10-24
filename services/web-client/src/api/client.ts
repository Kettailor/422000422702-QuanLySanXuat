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

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:3000/api';
const API_USERNAME = import.meta.env.VITE_API_USERNAME ?? 'admin';
const API_PASSWORD = import.meta.env.VITE_API_PASSWORD ?? 'admin123';

interface LoginResponse {
  token?: string;
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

export const authenticate = async (): Promise<string> => {
  const response = await fetch(`${API_BASE_URL}/auth/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      username: API_USERNAME,
      password: API_PASSWORD,
    }),
  });

  const data = await handleResponse<LoginResponse>(response, 'Không thể xác thực người dùng');
  if (!data.token) {
    throw new Error('Máy chủ không trả về mã xác thực');
  }

  return data.token;
};

export const fetchSummaryReport = async (token: string): Promise<SummaryResponse> => {
  const response = await fetch(`${API_BASE_URL}/reports/summary`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });

  return handleResponse<SummaryResponse>(response, 'Không thể tải báo cáo tổng quan');
};

export const apiConfig = {
  baseUrl: API_BASE_URL,
  username: API_USERNAME,
  password: API_PASSWORD,
};
