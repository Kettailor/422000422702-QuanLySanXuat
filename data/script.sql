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


INSERT INTO VAI_TRO (IdVaiTro, TenVaiTro) VALUES
('VT_ADMIN', 'Quản trị hệ thống'),
('VT_KETOAN', 'Kế toán'),
('VT_QUANLY', 'Quản lý xưởng');

INSERT INTO NHAN_VIEN (IdNhanVien, HoTen, NgaySinh, GioiTinh, ChucVu, HeSoLuong, TrangThai, DiaChi, ThoiGianLamViec, ChuKy) VALUES
('NV001', 'Nguyễn Thị Lan', '1985-05-10', 0, 'Quản đốc xưởng', 5, 'Đang làm việc', 'Khu phố 3, phường Phú Lợi, TP.Thủ Dầu Một', '2020-01-15 07:30:00', NULL),
('NV002', 'Trần Văn Minh', '1982-11-22', 1, 'Kỹ sư vận hành', 4, 'Đang làm việc', 'Đường N4, KCN VSIP 1, Bình Dương', '2019-07-01 07:30:00', NULL),
('NV003', 'Lê Hoàng Anh', '1990-03-18', 1, 'Tổ trưởng chuyền may', 3, 'Đang làm việc', 'Ấp 2, xã Phú An, Bến Cát', '2021-03-10 07:45:00', NULL),
('NV004', 'Phạm Thu Trang', '1992-08-05', 0, 'Thủ kho nguyên liệu', 3, 'Đang làm việc', 'Khu phố Đông, phường Hòa Phú, TP.Thủ Dầu Một', '2020-09-01 08:00:00', NULL),
('NV005', 'Đặng Quốc Việt', '1988-01-26', 1, 'Tổ trưởng đóng gói', 3, 'Đang làm việc', 'Phường Chánh Nghĩa, TP.Thủ Dầu Một', '2021-06-21 07:20:00', NULL),
('NV006', 'Vũ Hữu Tài', '1983-09-14', 1, 'Kế toán trưởng', 4, 'Đang làm việc', 'Khu phố 5, thị trấn Mỹ Phước, Bến Cát', '2018-04-02 08:05:00', NULL);

INSERT INTO XUONG (IdXuong, TenXuong, SlThietBi, SlNhanVien, TenQuyTrinh, NHANVIENSANXUAT_IdNhanVien, XUONGTRUONG_IdNhanVien) VALUES
('XU001', 'Xưởng May Thành Phẩm', 25, 40, 'May & hoàn thiện', 'NV003', 'NV001'),
('XU002', 'Xưởng Đóng Gói & Kiểm Định', 18, 28, 'Đóng gói & kiểm soát chất lượng', 'NV005', 'NV002');

INSERT INTO VAI_TRO (IdVaiTro, TenVaiTro) VALUES ('VT_KHACH', 'Khách hàng nội bộ') ON DUPLICATE KEY UPDATE TenVaiTro = VALUES(TenVaiTro);

INSERT INTO NGUOI_DUNG (IdNguoiDung, TenDangNhap, MatKhau, TrangThai, IdNhanVien, IdVaiTro) VALUES
('ND001', 'ql.lan', 'matkhau@123', 'Hoạt động', 'NV001', 'VT_QUANLY'),
('ND002', 'ketoan.tai', 'matkhau@123', 'Hoạt động', 'NV006', 'VT_KETOAN'),
('ND003', 'admin.minh', 'Matkhau!2023', 'Hoạt động', 'NV002', 'VT_ADMIN');

INSERT INTO KHACH_HANG (IdKhachHang, HoTen, GioiTinh, DiaChi, SoLuongDonHang, SoDienThoai, TongTien, LoaiKhachHang) VALUES
('KH001', 'Công ty Thực phẩm Ánh Dương', 1, 'Số 25 Nguyễn Huệ, Quận 1, TP.HCM', 12, '0283899123', 525000000, 'Bán buôn chiến lược'),
('KH002', 'Siêu thị Bình Minh', 1, 'Quốc lộ 13, phường Hiệp Bình Phước, TP.Thủ Đức', 18, '02837261111', 468000000, 'Đối tác siêu thị'),
('KH003', 'Cửa hàng Tạp hóa Cô Ba', 0, 'Ấp Phú Hòa, xã An Tây, Bến Cát', 9, '0913123456', 189000000, 'Đại lý khu vực');

INSERT INTO SAN_PHAM (IdSanPham, TenSanPham, DonVi, GiaBan, MoTa) VALUES
('SPBANH01', 'Bánh quy bơ sữa', 'Thùng', 450000, 'Bánh quy bơ thơm béo, đóng gói 24 gói nhỏ'),
('SPBANH02', 'Bánh quy socola chip', 'Thùng', 480000, 'Bánh quy socola chip giòn tan, hộp quà tặng'),
('SPBANH03', 'Bánh cracker mè đen', 'Thùng', 420000, 'Bánh cracker mè đen dinh dưỡng, gói 350g'),
('SPNL01', 'Bột mì số 8', 'Bao', 120000, 'Nguyên liệu chính cho khâu nhào bột'),
('SPNL02', 'Đường cát trắng tinh luyện', 'Bao', 135000, 'Đường tinh luyện dành cho thực phẩm cao cấp'),
('SPNL03', 'Bơ lạt New Zealand', 'Thùng', 890000, 'Bơ lạt nguyên chất dùng cho sản xuất bánh');

INSERT INTO KHO (IdKho, TenKho, TenLoaiKho, DiaChi, TongSLLo, ThanhTien, TrangThai, TongSL, IdXuong, NHAN_VIEN_KHO_IdNhanVien) VALUES
('KHO01', 'Kho Nguyên Liệu', 'Nguyên liệu', 'Lô A2, KCN Phúc An, Bến Cát', 3, 125600000, 'Đang sử dụng', 1500, 'XU001', 'NV004'),
('KHO02', 'Kho Thành Phẩm', 'Thành phẩm', 'Lô B1, KCN Phúc An, Bến Cát', 3, 235800000, 'Đang sử dụng', 1550, 'XU002', 'NV005');

INSERT INTO LO (IdLo, TenLo, SoLuong, NgayTao, LoaiLo, IdSanPham, IdKho) VALUES
('LOTP202309', 'Lô bánh quy bơ tháng 9', 500, '2023-09-10 08:00:00', 'Thành phẩm', 'SPBANH01', 'KHO02'),
('LOTP202310', 'Lô bánh quy socola tháng 10', 600, '2023-10-05 09:00:00', 'Thành phẩm', 'SPBANH02', 'KHO02'),
('LOTP202311', 'Lô bánh cracker mè đen tháng 11', 450, '2023-11-02 08:30:00', 'Thành phẩm', 'SPBANH03', 'KHO02'),
('LONL202309', 'Lô bột mì số 8 - 09/2023', 1000, '2023-09-01 07:45:00', 'Nguyên liệu', 'SPNL01', 'KHO01'),
('LONL202310', 'Lô đường tinh luyện - 10/2023', 800, '2023-10-03 08:15:00', 'Nguyên liệu', 'SPNL02', 'KHO01'),
('LONL202311', 'Lô bơ lạt - 11/2023', 600, '2023-11-04 09:20:00', 'Nguyên liệu', 'SPNL03', 'KHO01');

INSERT INTO NGUYEN_LIEU (IdNguyenLieu, TenNL, SoLuong, DonGian, TrangThai, NgaySanXuat, NgayHetHan, IdLo) VALUES
('NL001', 'Bột mì số 8', 400, 125000, 'Đang sử dụng', '2023-09-01 09:00:00', '2024-03-01 00:00:00', 'LONL202309'),
('NL002', 'Đường cát trắng', 300, 138000, 'Đang sử dụng', '2023-10-03 09:30:00', '2024-04-03 00:00:00', 'LONL202310'),
('NL003', 'Bơ lạt nguyên chất', 250, 900000, 'Đang sử dụng', '2023-11-04 10:15:00', '2024-05-04 00:00:00', 'LONL202311');

INSERT INTO DON_HANG (IdDonHang, YeuCau, TongTien, NgayLap, TrangThai, IdKhachHang) VALUES
('DH20231101', 'Giao hàng đợt 1 phục vụ mùa lễ hội cuối năm', 184500000, '2023-11-10', 'Đang xử lý', 'KH001'),
('DH20231105', 'Bổ sung hàng khuyến mãi Noel cho hệ thống siêu thị', 131400000, '2023-11-15', 'Đang xử lý', 'KH002'),
('DH20231202', 'Đơn chuẩn bị Tết Dương lịch', 134400000, '2023-12-02', 'Chờ giao', 'KH003');

INSERT INTO CT_DON_HANG (IdTTCTDonHang, SoLuong, NgayGiao, YeuCau, DonGia, ThanhTien, GhiChu, VAT, IdSanPham, IdDonHang) VALUES
('CTDH20231101A', 250, '2023-11-25 09:00:00', 'Đóng gói thùng carton in logo khách hàng', 450000, 112500000, 'Giao bằng xe lạnh', 0.08, 'SPBANH01', 'DH20231101'),
('CTDH20231101B', 150, '2023-11-26 13:30:00', 'Kiểm soát chất lượng 100% trước khi xuất kho', 480000, 72000000, 'Ưu tiên date mới', 0.08, 'SPBANH02', 'DH20231101'),
('CTDH20231105A', 180, '2023-11-28 08:00:00', 'Thêm tem khuyến mãi cuối năm', 450000, 81000000, 'Đóng gói theo pallet', 0.08, 'SPBANH01', 'DH20231105'),
('CTDH20231105B', 120, '2023-11-29 14:00:00', 'Bọc co nhiệt từng thùng', 420000, 50400000, 'Ghi chú nhiệt độ bảo quản 20°C', 0.08, 'SPBANH03', 'DH20231105'),
('CTDH20231202A', 140, '2023-12-20 10:00:00', 'In kèm thông điệp mừng năm mới', 480000, 67200000, 'Lịch giao buổi sáng', 0.08, 'SPBANH02', 'DH20231202'),
('CTDH20231202B', 160, '2023-12-22 15:00:00', 'Gia cố bìa carton hai lớp', 420000, 67200000, 'Yêu cầu xe nâng khi bốc hàng', 0.08, 'SPBANH03', 'DH20231202');

INSERT INTO KE_HOACH_SAN_XUAT (IdKeHoachSanXuat, SoLuong, ThoiGianKetThuc, TrangThai, ThoiGianBD, `BANIAMDOC IdNhanVien`, IdTTCTDonHang) VALUES
('KHSX20231101', 260, '2023-11-19 17:00:00', 'Đang triển khai', '2023-11-10 07:30:00', 'NV001', 'CTDH20231101A'),
('KHSX20231102', 170, '2023-11-22 17:30:00', 'Đang chuẩn bị', '2023-11-14 08:00:00', 'NV001', 'CTDH20231101B'),
('KHSX20231105', 195, '2023-11-26 16:00:00', 'Đang triển khai', '2023-11-18 07:30:00', 'NV001', 'CTDH20231105A'),
('KHSX20231202', 170, '2023-12-20 16:30:00', 'Đang lập kế hoạch', '2023-12-10 08:00:00', 'NV001', 'CTDH20231202B'),
('KHSX20231202A', 150, '2023-12-22 17:00:00', 'Chuẩn bị nguyên liệu', '2023-12-12 07:45:00', 'NV001', 'CTDH20231202A');

INSERT INTO KE_HOACH_SAN_XUAT_XUONG (IdKeHoachSanXuatXuong, TenThanhThanhPhanSP, SoLuong, ThoiGianBatDau, ThoiGianKetThuc, TrangThai, IdKeHoachSanXuat, IdXuong) VALUES
('KHSXX202311A', 'Nhào bột và tạo hình bánh bơ', 260, '2023-11-10 08:00:00', '2023-11-12 17:00:00', 'Đang làm', 'KHSX20231101', 'XU001'),
('KHSXX202311B', 'Đóng gói thành phẩm bánh bơ', 260, '2023-11-13 08:00:00', '2023-11-16 21:30:00', 'Đang làm', 'KHSX20231101', 'XU002'),
('KHSXX202311C', 'Phối trộn socola chip', 170, '2023-11-14 08:30:00', '2023-11-18 17:30:00', 'Chuẩn bị', 'KHSX20231102', 'XU001'),
('KHSXX202311D', 'Chuẩn bị bánh bơ khuyến mãi', 195, '2023-11-18 08:00:00', '2023-11-23 16:30:00', 'Đang làm', 'KHSX20231105', 'XU001'),
('KHSXX202312A', 'Đóng gói cracker mè đen', 170, '2023-12-12 08:00:00', '2023-12-18 17:00:00', 'Lập kế hoạch', 'KHSX20231202', 'XU002'),
('KHSXX202312B', 'Phối trộn socola dịp Tết', 150, '2023-12-12 09:00:00', '2023-12-20 18:00:00', 'Chuẩn bị', 'KHSX20231202A', 'XU001');

INSERT INTO CHI_TIET_KE_HOACH_SAN_XUAT_XUONG (IdCTKHSXX, SoLuong, IdKeHoachSanXuatXuong, IdNguyenLieu) VALUES
('CTKHSXX202311A', 320, 'KHSXX202311A', 'NL001'),
('CTKHSXX202311B', 120, 'KHSXX202311A', 'NL002'),
('CTKHSXX202311C', 90, 'KHSXX202311A', 'NL003'),
('CTKHSXX202311D', 140, 'KHSXX202311C', 'NL002'),
('CTKHSXX202311E', 80, 'KHSXX202311D', 'NL001'),
('CTKHSXX202312A', 110, 'KHSXX202312A', 'NL002'),
('CTKHSXX202312B', 70, 'KHSXX202312B', 'NL003');

INSERT INTO CA_LAM (IdCaLamViec, TenCa, LoaiCa, NgayLamViec, ThoiGianBatDau, ThoiGianKetThuc, TongSL, IdKeHoachSanXuatXuong, LOIdLo) VALUES
('CA202311S1', 'Ca sáng phối trộn bánh bơ', 'Sản xuất', '2023-11-11', '2023-11-11 07:30:00', '2023-11-11 15:30:00', 35, 'KHSXX202311A', 'LONL202309'),
('CA202311S2', 'Ca tối đóng gói bánh bơ', 'Đóng gói', '2023-11-13', '2023-11-13 14:00:00', '2023-11-13 22:00:00', 28, 'KHSXX202311B', 'LOTP202309'),
('CA202311S3', 'Ca đêm đóng gói tăng ca', 'Đóng gói', '2023-11-16', '2023-11-16 21:00:00', '2023-11-17 05:00:00', 24, 'KHSXX202311B', 'LOTP202309'),
('CA202312S1', 'Ca sáng chuẩn bị cracker', 'Chuẩn bị', '2023-12-12', '2023-12-12 07:30:00', '2023-12-12 15:30:00', 30, 'KHSXX202312A', 'LONL202310');

INSERT INTO CHAM_CONG (IdChamCong, `NHANVIEN IdNhanVien`, ThoiGIanRa, ThoiGianVao, `XUONGTRUONG IdNhanVien`, IdCaLamViec) VALUES
('CC2023111101', 'NV003', '2023-11-11 15:45:00', '2023-11-11 07:25:00', 'NV001', 'CA202311S1'),
('CC2023111102', 'NV002', '2023-11-11 16:00:00', '2023-11-11 07:20:00', 'NV001', 'CA202311S1'),
('CC2023111301', 'NV005', '2023-11-13 22:05:00', '2023-11-13 13:55:00', 'NV002', 'CA202311S2'),
('CC2023111601', 'NV005', '2023-11-17 05:10:00', '2023-11-16 20:55:00', 'NV002', 'CA202311S3'),
('CC2023121201', 'NV004', '2023-12-12 15:40:00', '2023-12-12 07:25:00', 'NV001', 'CA202312S1');

INSERT INTO BANG_LUONG (IdBangLuong, `KETOAN IdNhanVien2`, NHAN_VIENIdNhanVien, ThangNam, LuongCoBan, PhuCap, KhauTru, ThueTNCN, TongThuNhap, TrangThai, NgayLap, ChuKy) VALUES
('BL202311NV003', 'NV006', 'NV003', 202311, 8500000, 1200000, 500000, 700000, 9200000, 'Đã duyệt', '2023-11-28', NULL),
('BL202311NV004', 'NV006', 'NV004', 202311, 9000000, 1000000, 600000, 750000, 9350000, 'Đã duyệt', '2023-11-28', NULL),
('BL202311NV005', 'NV006', 'NV005', 202311, 9500000, 1300000, 650000, 780000, 10180000, 'Đã duyệt', '2023-11-28', NULL);

INSERT INTO PHIEU (IdPhieu, NgayLP, NgayXN, TongTien, LoaiPhieu, IdKho, NHAN_VIENIdNhanVien, NHAN_VIENIdNhanVien2) VALUES
('PN20231101', '2023-11-05', '2023-11-05', 35000000, 'Phiếu nhập nguyên liệu', 'KHO01', 'NV004', 'NV006'),
('PX20231102', '2023-11-12', '2023-11-12', 42000000, 'Phiếu xuất nguyên liệu', 'KHO01', 'NV004', 'NV001'),
('PX20231103', '2023-11-18', '2023-11-18', 68000000, 'Phiếu xuất thành phẩm', 'KHO02', 'NV005', 'NV002');

INSERT INTO CT_PHIEU (IdTTCTPhieu, DonViTinh, SoLuong, ThucNhan, IdPhieu, IdLo) VALUES
('CTP20231101A', 'Bao', 200, 200, 'PN20231101', 'LONL202309'),
('CTP20231101B', 'Bao', 150, 150, 'PN20231101', 'LONL202310'),
('CTP20231102A', 'Kg', 500, 500, 'PX20231102', 'LONL202309'),
('CTP20231102B', 'Kg', 350, 340, 'PX20231102', 'LONL202310'),
('CTP20231103A', 'Thùng', 220, 220, 'PX20231103', 'LOTP202309'),
('CTP20231103B', 'Thùng', 150, 148, 'PX20231103', 'LOTP202310');

INSERT INTO BIEN_BAN_DANH_GIA_THANH_PHAM (IdBienBanDanhGiaSP, ThoiGian, TongTCD, TongTCKD, KetQua, IdLo) VALUES
('BBTP20231101', '2023-11-18 09:30:00', 95, 5, 'Đạt', 'LOTP202309'),
('BBTP20231102', '2023-11-24 14:00:00', 93, 7, 'Đạt', 'LOTP202310'),
('BBTP20231103', '2023-11-28 10:15:00', 91, 9, 'Đạt có điều kiện', 'LOTP202311');

INSERT INTO TTCT_BIEN_BAN_DANH_GIA_THANH_PHAM (IdTTCTBBDGTP, Tieuchi, DiemD, GhiChu, HinhAnh, IdBienBanDanhGiaSP) VALUES
('CTBBTP20231101A', 'Độ giòn của bánh', 95, 'Giòn đều, không cháy xém', 'do_gion_banh.jpg', 'BBTP20231101'),
('CTBBTP20231101B', 'Bao bì & nhãn mác', 94, 'Tem in sắc nét, không trầy xước', 'bao_bi_banhbo.jpg', 'BBTP20231101'),
('CTBBTP20231102A', 'Hàm lượng socola', 93, 'Đạt chuẩn 12% socola chip', 'socola_chip.jpg', 'BBTP20231102'),
('CTBBTP20231102B', 'Kích thước đóng gói', 92, 'Đóng gói đúng quy cách 450g/thùng', 'kich_thuoc_thung.jpg', 'BBTP20231102'),
('CTBBTP20231103A', 'Hương vị mè đen', 91, 'Mè rang thơm nhưng cần tăng độ béo', 'huong_vi_me.jpg', 'BBTP20231103'),
('CTBBTP20231103B', 'Độ đồng đều sản phẩm', 90, 'Chênh lệch trọng lượng ±2g', 'dong_deu_sp.jpg', 'BBTP20231103');

INSERT INTO BIEN_BAN_DANH_GIA_DOT_XUAT (IdBienBanDanhGiaDX, ThoiGian, TongTCD, TongTCKD, KetQua, IdXuong, IdNhanVien) VALUES
('BBDX20231101', '2023-11-14 10:00:00', 92, 8, 'Đạt chuẩn', 'XU001', 'NV001'),
('BBDX20231102', '2023-11-20 09:00:00', 90, 10, 'Đạt chuẩn', 'XU002', 'NV002');

INSERT INTO TTCT_BIEN_BAN_DANH_GIA_DOT_XUAT (IdTTCTBBDGDX, LoaiTieuChi, TieuChi, DiemDG, GhiChu, HinhAnh, IdBienBanDanhGiaDX) VALUES
('CTBBDX20231101A', 'An toàn lao động', 'Trang bị bảo hộ lao động', 92, 'Đủ trang bị bảo hộ theo quy định', 'bao_ho_lao_dong.jpg', 'BBDX20231101'),
('CTBBDX20231101B', 'Vệ sinh công nghiệp', 'Vệ sinh dây chuyền sản xuất', 90, 'Cần bổ sung vệ sinh khu vực trộn bột cuối ca', 've_sinh_day_chuyen.jpg', 'BBDX20231101'),
('CTBBDX20231102A', 'Kiểm soát đóng gói', 'Niêm phong thùng thành phẩm', 91, 'Niêm phong chắc chắn, tem còn nguyên', 'dong_goi_chat_luong.jpg', 'BBDX20231102'),
('CTBBDX20231102B', 'Hồ sơ truy xuất', 'Cập nhật nhật ký sản xuất', 89, 'Đề nghị cập nhật thêm nhật ký giao ca', 'nhat_ky_san_xuat.jpg', 'BBDX20231102');

INSERT INTO HOA_DON (IdHoaDon, NgayLap, TrangThai, LoaiHD, IdDonHang) VALUES
('HD20231101', '2023-11-20', 'Đã phát hành', 'Hóa đơn GTGT', 'DH20231101'),
('HD20231105', '2023-11-25', 'Đã phát hành', 'Hóa đơn GTGT', 'DH20231105'),
('HD20231202', '2023-12-05', 'Chờ thanh toán', 'Hóa đơn GTGT', 'DH20231202');

INSERT INTO CT_HOA_DON (IdCTHoaDon, SoLuong, ThueVAT, TongTien, PhuongThucTT, IdHoaDon, IdLo) VALUES
('CTHD20231101A', 250, 8, 121500000, 'Chuyển khoản', 'HD20231101', 'LOTP202309'),
('CTHD20231101B', 150, 8, 77760000, 'Chuyển khoản', 'HD20231101', 'LOTP202310'),
('CTHD20231105A', 180, 8, 87480000, 'Chuyển khoản', 'HD20231105', 'LOTP202309'),
('CTHD20231105B', 120, 8, 54432000, 'Chuyển khoản', 'HD20231105', 'LOTP202311'),
('CTHD20231202A', 140, 8, 72576000, 'Chuyển khoản', 'HD20231202', 'LOTP202310'),
('CTHD20231202B', 160, 8, 72576000, 'Chuyển khoản', 'HD20231202', 'LOTP202311');

INSERT INTO THANH_PHAM (IdThanhPham, TenThanhPham, YeuCau, DonGia, LoaiTP, IdLo) VALUES
('TP20231101', 'Bánh quy bơ thùng 24 gói', 'Đảm bảo đóng gói kín, tem chống hàng giả', 470000, 'Loại A', 'LOTP202309'),
('TP20231102', 'Bánh quy socola hộp quà', 'Bao bì in theo yêu cầu Noel', 520000, 'Loại A+', 'LOTP202310'),
('TP20231103', 'Bánh cracker mè đen bịch lớn', 'Phân phối thử nghiệm miền Tây', 390000, 'Loại B', 'LOTP202311');

INSERT INTO HOAT_DONG_HE_THONG (IdHoatDong, HanhDong, ThoiGian, IdNguoiDung) VALUES
('HDHT2023110101', 'Tạo kế hoạch sản xuất KHSX20231101', '2023-11-09 08:15:00', 'ND001'),
('HDHT2023111201', 'Duyệt phiếu xuất PX20231102', '2023-11-12 09:45:00', 'ND002'),
('HDHT2023112001', 'Cập nhật tiến độ đóng gói lô LOTP202309', '2023-11-20 16:20:00', 'ND003');
