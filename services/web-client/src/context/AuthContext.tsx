import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useRef,
  useState,
  type ReactNode,
} from 'react';
import {
  AuthUser,
  getSession as getSessionRequest,
  login as loginRequest,
  logout as logoutRequest,
} from '../api/client';

const TOKEN_STORAGE_KEY = 'qlsx.session.token';

const tokenStorage = {
  get(): string | null {
    if (typeof window === 'undefined') {
      return null;
    }
    try {
      return window.sessionStorage.getItem(TOKEN_STORAGE_KEY);
    } catch (error) {
      console.warn('Không thể đọc token từ sessionStorage', error);
      return null;
    }
  },
  set(token: string) {
    if (typeof window === 'undefined') {
      return;
    }
    try {
      window.sessionStorage.setItem(TOKEN_STORAGE_KEY, token);
    } catch (error) {
      console.warn('Không thể lưu token vào sessionStorage', error);
    }
  },
  clear() {
    if (typeof window === 'undefined') {
      return;
    }
    try {
      window.sessionStorage.removeItem(TOKEN_STORAGE_KEY);
    } catch (error) {
      console.warn('Không thể xóa token khỏi sessionStorage', error);
    }
  },
};

export interface AuthContextValue {
  token: string | null;
  user: AuthUser | null;
  initializing: boolean;
  login: (credentials: { username: string; password: string }) => Promise<void>;
  logout: () => Promise<void>;
  handleUnauthorized: () => Promise<void>;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [token, setToken] = useState<string | null>(null);
  const [user, setUser] = useState<AuthUser | null>(null);
  const [initializing, setInitializing] = useState(true);
  const restoring = useRef(false);

  const clearSession = useCallback(() => {
    setToken(null);
    setUser(null);
    tokenStorage.clear();
  }, []);

  const restoreSession = useCallback(async () => {
    if (restoring.current) {
      return;
    }

    restoring.current = true;
    try {
      const storedToken = tokenStorage.get();
      if (!storedToken) {
        clearSession();
        return;
      }

      const session = await getSessionRequest(storedToken);
      setToken(session.token);
      setUser(session.user ?? null);
      tokenStorage.set(session.token);
    } catch (error) {
      console.warn('Không thể khôi phục phiên đăng nhập', error);
      clearSession();
    } finally {
      restoring.current = false;
      setInitializing(false);
    }
  }, [clearSession]);

  useEffect(() => {
    restoreSession().catch((error) => {
      console.error('Lỗi khôi phục phiên đăng nhập', error);
      setInitializing(false);
    });
  }, [restoreSession]);

  const login = useCallback(
    async ({ username, password }: { username: string; password: string }) => {
      const result = await loginRequest({ username, password });
      setToken(result.token);
      setUser(result.user ?? { username });
      tokenStorage.set(result.token);
      setInitializing(false);
    },
    []
  );

  const logout = useCallback(async () => {
    if (token) {
      try {
        await logoutRequest(token);
      } catch (error) {
        console.warn('Không thể gửi yêu cầu đăng xuất', error);
      }
    }
    clearSession();
  }, [clearSession, token]);

  const handleUnauthorized = useCallback(async () => {
    await logout();
  }, [logout]);

  const value = useMemo<AuthContextValue>(
    () => ({ token, user, initializing, login, logout, handleUnauthorized }),
    [handleUnauthorized, initializing, login, logout, token, user]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = (): AuthContextValue => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth phải được sử dụng cùng AuthProvider');
  }
  return context;
};
