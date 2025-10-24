import { ChangeEvent, FormEvent, useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const initialFormState = {
  username: 'admin',
  password: 'admin123',
};

export default function Login() {
  const { login, token, initializing } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState(initialFormState);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!initializing && token) {
      navigate('/dashboard/overview', { replace: true });
    }
  }, [initializing, navigate, token]);

  const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
    const { name, value } = event.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setSubmitting(true);
    setError(null);

    try {
      await login({ username: form.username.trim(), password: form.password });
      navigate('/dashboard/overview', { replace: true });
    } catch (submitError) {
      setError((submitError as Error).message || 'Đăng nhập thất bại');
    } finally {
      setSubmitting(false);
    }
  };

  if (initializing) {
    return (
      <div className="page-loading">
        <div className="page-loading__spinner" aria-hidden />
        <p>Đang kiểm tra phiên đăng nhập...</p>
      </div>
    );
  }

  return (
    <div className="login-page">
      <div className="login-card">
        <div className="login-card__brand">
          <span role="img" aria-hidden="true">
            🏭
          </span>
          <div>
            <h1>Sinh Viên 5 Tốt ERP</h1>
            <p>Hệ thống quản lý sản xuất cho đội ngũ vận hành</p>
          </div>
        </div>

        <form className="login-form" onSubmit={handleSubmit}>
          <h2>Đăng nhập hệ thống</h2>
          <p className="login-form__subtitle">Sử dụng tài khoản được cấp để truy cập</p>

          {error ? <div className="login-form__error">{error}</div> : null}

          <label className="login-form__field">
            <span>Tên đăng nhập</span>
            <input
              autoComplete="username"
              name="username"
              value={form.username}
              onChange={handleChange}
              placeholder="Nhập tên đăng nhập"
              disabled={submitting}
              required
            />
          </label>

          <label className="login-form__field">
            <span>Mật khẩu</span>
            <input
              type="password"
              autoComplete="current-password"
              name="password"
              value={form.password}
              onChange={handleChange}
              placeholder="Nhập mật khẩu"
              disabled={submitting}
              required
            />
          </label>

          <button type="submit" className="login-form__submit" disabled={submitting}>
            {submitting ? 'Đang xử lý...' : 'Đăng nhập'}
          </button>

          <p className="login-form__hint">
            Tài khoản mẫu: <strong>admin / admin123</strong>
          </p>
        </form>
      </div>
    </div>
  );
}
