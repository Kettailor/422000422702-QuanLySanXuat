CREATE TABLE BIEN_BAN_DANH_GIA_THANH_PHAM (IdBienBanDanhGiaSP varchar(50) NOT NULL, ThoiGian datetime NULL, TongTCD int(10), TongTCKD int(10), KetQua varchar(255), IdLo varchar(50) NOT NULL, PRIMARY KEY (IdBienBanDanhGiaSP));
CREATE TABLE DON_HANG (IdDonHang varchar(50) NOT NULL, YeuCau text, TongTien float, NgayLap date, TrangThai varchar(255), IdKhachHang varchar(50) NOT NULL, PRIMARY KEY (IdDonHang));
CREATE TABLE CT_DON_HANG (IdTTCTDonHang varchar(50) NOT NULL, SoLuong int(10), NgayGiao datetime NULL, YeuCau text, DonGia float, ThanhTien float, GhiChu text, VAT float, IdSanPham varchar(50) NOT NULL, IdDonHang varchar(50) NOT NULL, PRIMARY KEY (IdTTCTDonHang));
CREATE TABLE NHAN_VIEN (IdNhanVien varchar(50) NOT NULL, HoTen varchar(255), NgaySinh date, GioiTinh tinyint(3), ChucVu varchar(255), HeSoLuong int(10), TrangThai varchar(255), DiaChi varchar(255), ThoiGianLamViec datetime NULL, ChuKy varbinary(2000), PRIMARY KEY (IdNhanVien));
CREATE TABLE XUONG (IdXuong varchar(50) NOT NULL, TenXuong varchar(255), SlThietBi int(10), SlNhanVien int(10), TenQuyTrinh varchar(255), NHANVIENSANXUAT_IdNhanVien varchar(50) NOT NULL, XUONGTRUONG_IdNhanVien varchar(50) NOT NULL, PRIMARY KEY (IdXuong));
CREATE TABLE HOAT_DONG_HE_THONG (IdHoatDong varchar(50) NOT NULL, HanhDong text, ThoiGian datetime NULL, IdNguoiDung varchar(50) NOT NULL, PRIMARY KEY (IdHoatDong));
CREATE TABLE HOA_DON (IdHoaDon varchar(50) NOT NULL, NgayLap date, TrangThai varchar(255), LoaiHD varchar(255), IdDonHang varchar(50) NOT NULL, PRIMARY KEY (IdHoaDon));
CREATE TABLE PHIEU (IdPhieu varchar(50) NOT NULL, NgayLP date, NgayXN date, TongTien int(10), LoaiPhieu varchar(255), IdKho varchar(50) NOT NULL, NHAN_VIENIdNhanVien varchar(50) NOT NULL, NHAN_VIENIdNhanVien2 varchar(50) NOT NULL, PRIMARY KEY (IdPhieu));
CREATE TABLE KHO (IdKho varchar(50) NOT NULL, TenKho varchar(255), TenLoaiKho varchar(255), DiaChi varchar(255), TongSLLo int(10), ThanhTien int(10), TrangThai varchar(255), TongSL int(10), IdXuong varchar(50) NOT NULL, NHAN_VIEN_KHO_IdNhanVien varchar(50) NOT NULL, PRIMARY KEY (IdKho));
CREATE TABLE NGUYEN_LIEU (IdNguyenLieu varchar(50) NOT NULL, TenNL varchar(255), SoLuong int(10), DonGian float, TrangThai varchar(255), NgaySanXuat datetime NULL, NgayHetHan datetime NULL, IdLo varchar(50) NOT NULL, PRIMARY KEY (IdNguyenLieu));
CREATE TABLE TTCT_BIEN_BAN_DANH_GIA_DOT_XUAT (IdTTCTBBDGDX varchar(50) NOT NULL, LoaiTieuChi varchar(255), TieuChi varchar(255), DiemDG int(10), GhiChu varchar(255), HinhAnh varchar(255), IdBienBanDanhGiaDX varchar(50) NOT NULL, PRIMARY KEY (IdTTCTBBDGDX));
CREATE TABLE SAN_PHAM (IdSanPham varchar(50) NOT NULL, TenSanPham varchar(255), DonVi varchar(255), GiaBan float, MoTa varchar(255), PRIMARY KEY (IdSanPham));
CREATE TABLE BIEN_BAN_DANH_GIA_DOT_XUAT (IdBienBanDanhGiaDX varchar(50) NOT NULL, ThoiGian datetime NULL, TongTCD int(10), TongTCKD int(10), KetQua varchar(255), IdXuong varchar(50) NOT NULL, IdNhanVien varchar(50) NOT NULL, PRIMARY KEY (IdBienBanDanhGiaDX));
CREATE TABLE BANG_LUONG (IdBangLuong varchar(50) NOT NULL, `KETOAN IdNhanVien2` varchar(50) NOT NULL, NHAN_VIENIdNhanVien varchar(50) NOT NULL, ThangNam int(11), LuongCoBan float, PhuCap int(10), KhauTru float, ThueTNCN int(10), TongThuNhap int(10), TrangThai varchar(255), NgayLap date, ChuKy varbinary(2000), PRIMARY KEY (IdBangLuong));
CREATE TABLE CHAM_CONG (IdChamCong varchar(50) NOT NULL, `NHANVIEN IdNhanVien` varchar(50) NOT NULL, ThoiGIanRa datetime NULL, ThoiGianVao datetime NULL, `XUONGTRUONG IdNhanVien` varchar(50) NOT NULL, IdCaLamViec varchar(50) NOT NULL, PRIMARY KEY (IdChamCong));
CREATE TABLE LO (IdLo varchar(50) NOT NULL, TenLo varchar(255), SoLuong int(10), NgayTao datetime NULL, LoaiLo varchar(255), IdSanPham varchar(50) NOT NULL, IdKho varchar(50) NOT NULL, PRIMARY KEY (IdLo));
CREATE TABLE TTCT_BIEN_BAN_DANH_GIA_THANH_PHAM (IdTTCTBBDGTP varchar(50) NOT NULL, Tieuchi varchar(255), DiemD int(10), GhiChu varchar(255), HinhAnh varchar(255), IdBienBanDanhGiaSP varchar(50) NOT NULL, PRIMARY KEY (IdTTCTBBDGTP));
CREATE TABLE CT_HOA_DON (IdCTHoaDon varchar(50) NOT NULL, SoLuong int(10), ThueVAT int(10), TongTien int(10), PhuongThucTT varchar(255), IdHoaDon varchar(50) NOT NULL, IdLo varchar(50) NOT NULL, PRIMARY KEY (IdCTHoaDon));
CREATE TABLE CHI_TIET_KE_HOACH_SAN_XUAT_XUONG (IdCTKHSXX varchar(50) NOT NULL, SoLuong int(10), IdKeHoachSanXuatXuong varchar(50) NOT NULL, IdNguyenLieu varchar(50) NOT NULL, PRIMARY KEY (IdCTKHSXX));
CREATE TABLE KE_HOACH_SAN_XUAT_XUONG (IdKeHoachSanXuatXuong varchar(50) NOT NULL, TenThanhThanhPhanSP varchar(255), SoLuong int(10), ThoiGianBatDau datetime NULL, ThoiGianKetThuc datetime NULL, TrangThai varchar(255), IdKeHoachSanXuat varchar(50) NOT NULL, IdXuong varchar(50) NOT NULL, PRIMARY KEY (IdKeHoachSanXuatXuong));
CREATE TABLE CT_PHIEU (IdTTCTPhieu varchar(50) NOT NULL, DonViTinh varchar(255), SoLuong int(10), ThucNhan int(10), IdPhieu varchar(50) NOT NULL, IdLo varchar(50) NOT NULL, PRIMARY KEY (IdTTCTPhieu));
CREATE TABLE NGUOI_DUNG (IdNguoiDung varchar(50) NOT NULL, TenDangNhap varchar(255), MatKhau varchar(255), TrangThai varchar(255), IdNhanVien varchar(50) NOT NULL, IdVaiTro varchar(50) NOT NULL, PRIMARY KEY (IdNguoiDung));
CREATE TABLE CA_LAM (IdCaLamViec varchar(50) NOT NULL, TenCa varchar(255), LoaiCa varchar(255), NgayLamViec date, ThoiGianBatDau datetime NULL, ThoiGianKetThuc datetime NULL, TongSL int(10), IdKeHoachSanXuatXuong varchar(50) NOT NULL, LOIdLo varchar(50) NOT NULL, PRIMARY KEY (IdCaLamViec));
CREATE TABLE KHACH_HANG (IdKhachHang varchar(50) NOT NULL, HoTen varchar(255), GioiTinh tinyint(3), DiaChi varchar(255), SoLuongDonHang int(10), SoDienThoai varchar(12), TongTien float, LoaiKhachHang varchar(255), PRIMARY KEY (IdKhachHang));
CREATE TABLE VAI_TRO (IdVaiTro varchar(50) NOT NULL, TenVaiTro varchar(255), PRIMARY KEY (IdVaiTro));
CREATE TABLE THANH_PHAM (IdThanhPham varchar(50) NOT NULL, TenThanhPham varchar(255), YeuCau text, DonGia int(10), LoaiTP varchar(255), IdLo varchar(50) NOT NULL, PRIMARY KEY (IdThanhPham));
CREATE TABLE KE_HOACH_SAN_XUAT (IdKeHoachSanXuat varchar(50) NOT NULL, SoLuong int(10), ThoiGianKetThuc datetime NULL, TrangThai varchar(255), ThoiGianBD datetime NULL, `BANIAMDOC IdNhanVien` varchar(50) NOT NULL, IdTTCTDonHang varchar(50) NOT NULL, PRIMARY KEY (IdKeHoachSanXuat));
ALTER TABLE BANG_LUONG ADD CONSTRAINT `Ke toan` FOREIGN KEY (NHAN_VIENIdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE BANG_LUONG ADD CONSTRAINT `Nhan vien co bang luong` FOREIGN KEY (`KETOAN IdNhanVien2`) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CHAM_CONG ADD CONSTRAINT `Nhan vien duoc cham cong` FOREIGN KEY (`NHANVIEN IdNhanVien`) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CT_DON_HANG ADD CONSTRAINT FKCT_DON_HAN864902 FOREIGN KEY (IdSanPham) REFERENCES SAN_PHAM (IdSanPham);
ALTER TABLE TTCT_BIEN_BAN_DANH_GIA_THANH_PHAM ADD CONSTRAINT FKTTCT_BIEN_864028 FOREIGN KEY (IdBienBanDanhGiaSP) REFERENCES BIEN_BAN_DANH_GIA_THANH_PHAM (IdBienBanDanhGiaSP);
ALTER TABLE BIEN_BAN_DANH_GIA_DOT_XUAT ADD CONSTRAINT FKBIEN_BAN_D429844 FOREIGN KEY (IdXuong) REFERENCES XUONG (IdXuong);
ALTER TABLE NGUOI_DUNG ADD CONSTRAINT FKNGUOI_DUNG977062 FOREIGN KEY (IdVaiTro) REFERENCES VAI_TRO (IdVaiTro);
ALTER TABLE PHIEU ADD CONSTRAINT FKPHIEU116698 FOREIGN KEY (IdKho) REFERENCES KHO (IdKho);
ALTER TABLE CT_PHIEU ADD CONSTRAINT FKCT_PHIEU378026 FOREIGN KEY (IdPhieu) REFERENCES PHIEU (IdPhieu);
ALTER TABLE TTCT_BIEN_BAN_DANH_GIA_DOT_XUAT ADD CONSTRAINT FKTTCT_BIEN_760467 FOREIGN KEY (IdBienBanDanhGiaDX) REFERENCES BIEN_BAN_DANH_GIA_DOT_XUAT (IdBienBanDanhGiaDX);
ALTER TABLE HOA_DON ADD CONSTRAINT FKHOA_DON917821 FOREIGN KEY (IdDonHang) REFERENCES DON_HANG (IdDonHang);
ALTER TABLE XUONG ADD CONSTRAINT `Nhan vien san xuat` FOREIGN KEY (NHANVIENSANXUAT_IdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE LO ADD CONSTRAINT FKLO20048 FOREIGN KEY (IdKho) REFERENCES KHO (IdKho);
ALTER TABLE NGUYEN_LIEU ADD CONSTRAINT FKNGUYEN_LIE587750 FOREIGN KEY (IdLo) REFERENCES LO (IdLo);
ALTER TABLE KE_HOACH_SAN_XUAT_XUONG ADD CONSTRAINT FKKE_HOACH_S948077 FOREIGN KEY (IdXuong) REFERENCES XUONG (IdXuong);
ALTER TABLE LO ADD CONSTRAINT FKLO159940 FOREIGN KEY (IdSanPham) REFERENCES SAN_PHAM (IdSanPham);
ALTER TABLE KHO ADD CONSTRAINT `Nhan vien kho` FOREIGN KEY (NHAN_VIEN_KHO_IdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE XUONG ADD CONSTRAINT `Xuong truong` FOREIGN KEY (XUONGTRUONG_IdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CT_PHIEU ADD CONSTRAINT FKCT_PHIEU491583 FOREIGN KEY (IdLo) REFERENCES LO (IdLo);
ALTER TABLE BIEN_BAN_DANH_GIA_THANH_PHAM ADD CONSTRAINT FKBIEN_BAN_D733684 FOREIGN KEY (IdLo) REFERENCES LO (IdLo);
ALTER TABLE CHI_TIET_KE_HOACH_SAN_XUAT_XUONG ADD CONSTRAINT FKCHI_TIET_K933945 FOREIGN KEY (IdNguyenLieu) REFERENCES NGUYEN_LIEU (IdNguyenLieu);
ALTER TABLE CHAM_CONG ADD CONSTRAINT `Nhan vien cham cong` FOREIGN KEY (`XUONGTRUONG IdNhanVien`) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CA_LAM ADD CONSTRAINT FKCA_LAM945015 FOREIGN KEY (IdKeHoachSanXuatXuong) REFERENCES KE_HOACH_SAN_XUAT_XUONG (IdKeHoachSanXuatXuong);
ALTER TABLE CHAM_CONG ADD CONSTRAINT FKCHAM_CONG958641 FOREIGN KEY (IdCaLamViec) REFERENCES CA_LAM (IdCaLamViec);
ALTER TABLE HOAT_DONG_HE_THONG ADD CONSTRAINT FKHOAT_DONG_87294 FOREIGN KEY (IdNguoiDung) REFERENCES NGUOI_DUNG (IdNguoiDung);
ALTER TABLE DON_HANG ADD CONSTRAINT FKDON_HANG579482 FOREIGN KEY (IdKhachHang) REFERENCES KHACH_HANG (IdKhachHang);
ALTER TABLE CA_LAM ADD CONSTRAINT FKCA_LAM557411 FOREIGN KEY (LOIdLo) REFERENCES LO (IdLo);
ALTER TABLE NGUOI_DUNG ADD CONSTRAINT FKNGUOI_DUNG547019 FOREIGN KEY (IdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CHI_TIET_KE_HOACH_SAN_XUAT_XUONG ADD CONSTRAINT FKCHI_TIET_K954837 FOREIGN KEY (IdKeHoachSanXuatXuong) REFERENCES KE_HOACH_SAN_XUAT_XUONG (IdKeHoachSanXuatXuong);
ALTER TABLE BIEN_BAN_DANH_GIA_DOT_XUAT ADD CONSTRAINT `Nhan vien kiem soat chat luong` FOREIGN KEY (IdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CT_HOA_DON ADD CONSTRAINT FKCT_HOA_DON878731 FOREIGN KEY (IdHoaDon) REFERENCES HOA_DON (IdHoaDon);
ALTER TABLE KE_HOACH_SAN_XUAT ADD CONSTRAINT FKKE_HOACH_S473207 FOREIGN KEY (IdTTCTDonHang) REFERENCES CT_DON_HANG (IdTTCTDonHang);
ALTER TABLE KE_HOACH_SAN_XUAT ADD CONSTRAINT FKKE_HOACH_S691979 FOREIGN KEY (`BANIAMDOC IdNhanVien`) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CT_HOA_DON ADD CONSTRAINT FKCT_HOA_DON632652 FOREIGN KEY (IdLo) REFERENCES LO (IdLo);
ALTER TABLE KE_HOACH_SAN_XUAT_XUONG ADD CONSTRAINT FKKE_HOACH_S948390 FOREIGN KEY (IdKeHoachSanXuat) REFERENCES KE_HOACH_SAN_XUAT (IdKeHoachSanXuat);
ALTER TABLE PHIEU ADD CONSTRAINT `Nhan vien lap phieu` FOREIGN KEY (NHAN_VIENIdNhanVien) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE THANH_PHAM ADD CONSTRAINT FKTHANH_PHAM566175 FOREIGN KEY (IdLo) REFERENCES LO (IdLo);
ALTER TABLE PHIEU ADD CONSTRAINT `Nhan vien xac nhan phieu` FOREIGN KEY (NHAN_VIENIdNhanVien2) REFERENCES NHAN_VIEN (IdNhanVien);
ALTER TABLE CT_DON_HANG ADD CONSTRAINT FKCT_DON_HAN479798 FOREIGN KEY (IdDonHang) REFERENCES DON_HANG (IdDonHang);
ALTER TABLE KHO ADD CONSTRAINT FKKHO901694 FOREIGN KEY (IdXuong) REFERENCES XUONG (IdXuong);

CREATE TABLE IF NOT EXISTS production_lines (
  id SERIAL PRIMARY KEY,
  code VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  department VARCHAR(120) NOT NULL,
  status VARCHAR(40) NOT NULL DEFAULT 'active',
  target_oee NUMERIC(5,2) DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS shifts (
  id SERIAL PRIMARY KEY,
  shift_date DATE NOT NULL,
  shift_name VARCHAR(60) NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  UNIQUE (shift_date, shift_name)
);

CREATE TABLE IF NOT EXISTS work_orders (
  id SERIAL PRIMARY KEY,
  order_code VARCHAR(40) UNIQUE NOT NULL,
  line_id INTEGER NOT NULL REFERENCES production_lines (id) ON DELETE RESTRICT,
  product_code VARCHAR(40) NOT NULL,
  planned_quantity INTEGER NOT NULL,
  completed_quantity INTEGER NOT NULL DEFAULT 0,
  scrap_quantity INTEGER NOT NULL DEFAULT 0,
  status VARCHAR(40) NOT NULL,
  due_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS line_shift_metrics (
  id SERIAL PRIMARY KEY,
  line_id INTEGER NOT NULL REFERENCES production_lines (id) ON DELETE CASCADE,
  shift_id INTEGER NOT NULL REFERENCES shifts (id) ON DELETE CASCADE,
  planned_output INTEGER NOT NULL,
  actual_output INTEGER NOT NULL,
  downtime_minutes INTEGER NOT NULL DEFAULT 0,
  note VARCHAR(255),
  UNIQUE (line_id, shift_id)
);

INSERT INTO production_lines (code, name, department, status, target_oee)
VALUES
  ('PL-01', 'Chuyền lắp ráp 01', 'Lắp ráp', 'active', 0.87),
  ('PL-02', 'Chuyền kiểm thử 02', 'Kiểm thử', 'active', 0.83),
  ('PL-03', 'Chuyền đóng gói 03', 'Đóng gói', 'active', 0.80),
  ('PL-04', 'Chuyền cơ khí 04', 'Gia công', 'maintenance', 0.78)
ON CONFLICT (code) DO NOTHING;

INSERT INTO shifts (shift_date, shift_name, start_time, end_time)
VALUES
  (CURRENT_DATE - INTERVAL '2 day', 'Ca sáng', '06:00', '14:00'),
  (CURRENT_DATE - INTERVAL '2 day', 'Ca tối', '14:00', '22:00'),
  (CURRENT_DATE - INTERVAL '1 day', 'Ca sáng', '06:00', '14:00'),
  (CURRENT_DATE - INTERVAL '1 day', 'Ca tối', '14:00', '22:00'),
  (CURRENT_DATE, 'Ca sáng', '06:00', '14:00'),
  (CURRENT_DATE, 'Ca tối', '14:00', '22:00')
ON CONFLICT (shift_date, shift_name) DO NOTHING;

INSERT INTO work_orders (
  order_code,
  line_id,
  product_code,
  planned_quantity,
  completed_quantity,
  scrap_quantity,
  status,
  due_time
)
SELECT data.order_code,
       pl.id,
       data.product_code,
       data.planned_quantity,
       data.completed_quantity,
       data.scrap_quantity,
       data.status,
       data.due_time
FROM (
  VALUES
    ('WO-2025-001', 'PL-01', 'SPK-101', 450, 420, 8, 'in_progress', NOW() + INTERVAL '1 day'),
    ('WO-2025-002', 'PL-02', 'SPK-201', 300, 300, 2, 'completed', NOW() - INTERVAL '6 hour'),
    ('WO-2025-003', 'PL-03', 'SPK-305', 520, 480, 15, 'in_progress', NOW() + INTERVAL '2 day'),
    ('WO-2025-004', 'PL-04', 'SPK-410', 260, 140, 5, 'planned', NOW() + INTERVAL '3 day'),
    ('WO-2025-005', 'PL-01', 'SPK-109', 380, 360, 4, 'completed', NOW() - INTERVAL '1 day'),
    ('WO-2025-006', 'PL-02', 'SPK-220', 420, 0, 0, 'planned', NOW() + INTERVAL '4 day')
) AS data(order_code, line_code, product_code, planned_quantity, completed_quantity, scrap_quantity, status, due_time)
JOIN production_lines pl ON pl.code = data.line_code
ON CONFLICT (order_code) DO NOTHING;

WITH metrics AS (
  SELECT pl.code AS line_code,
         s.shift_date,
         s.shift_name,
         data.planned_output,
         data.actual_output,
         data.downtime_minutes
  FROM (
    VALUES
      ('PL-01', CURRENT_DATE - INTERVAL '2 day', 'Ca sáng', 400, 388, 20),
      ('PL-01', CURRENT_DATE - INTERVAL '1 day', 'Ca sáng', 420, 405, 18),
      ('PL-01', CURRENT_DATE, 'Ca sáng', 430, 410, 15),
      ('PL-01', CURRENT_DATE, 'Ca tối', 380, 350, 32),
      ('PL-02', CURRENT_DATE - INTERVAL '2 day', 'Ca tối', 320, 310, 12),
      ('PL-02', CURRENT_DATE - INTERVAL '1 day', 'Ca sáng', 340, 330, 10),
      ('PL-02', CURRENT_DATE, 'Ca sáng', 360, 355, 6),
      ('PL-03', CURRENT_DATE - INTERVAL '1 day', 'Ca tối', 500, 470, 25),
      ('PL-03', CURRENT_DATE, 'Ca sáng', 520, 500, 14),
      ('PL-04', CURRENT_DATE - INTERVAL '2 day', 'Ca sáng', 260, 190, 45),
      ('PL-04', CURRENT_DATE - INTERVAL '1 day', 'Ca tối', 280, 200, 60)
  ) AS data(line_code, shift_date, shift_name, planned_output, actual_output, downtime_minutes)
  JOIN production_lines pl ON pl.code = data.line_code
  JOIN shifts s ON s.shift_date = data.shift_date AND s.shift_name = data.shift_name
)
INSERT INTO line_shift_metrics (line_id, shift_id, planned_output, actual_output, downtime_minutes)
SELECT pl.id, s.id, m.planned_output, m.actual_output, m.downtime_minutes
FROM metrics m
JOIN production_lines pl ON pl.code = m.line_code
JOIN shifts s ON s.shift_date = m.shift_date AND s.shift_name = m.shift_name
ON CONFLICT (line_id, shift_id) DO NOTHING;
