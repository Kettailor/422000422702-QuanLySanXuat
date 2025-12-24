-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 23, 2025 lúc 08:41 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `422000422702-quanlysanxuat`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_luong`
--

CREATE TABLE `bang_luong` (
  `IdBangLuong` varchar(50) NOT NULL,
  `KETOAN IdNhanVien2` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien` varchar(50) NOT NULL,
  `ThangNam` int(11) DEFAULT NULL,
  `LuongCoBan` float DEFAULT NULL,
  `PhuCap` float DEFAULT NULL,
  `DonGiaNgayCong` float DEFAULT NULL,
  `SoNgayCong` float DEFAULT NULL,
  `TongLuongNgayCong` float DEFAULT NULL,
  `Thuong` float DEFAULT NULL,
  `KhauTru` float DEFAULT NULL,
  `TongBaoHiem` float DEFAULT NULL,
  `ThueTNCN` float DEFAULT NULL,
  `TongThuNhap` float DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `NgayLap` date DEFAULT NULL,
  `ChuKy` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_ban_danh_gia_dot_xuat`
--

CREATE TABLE `bien_ban_danh_gia_dot_xuat` (
  `IdBienBanDanhGiaDX` varchar(50) NOT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `TongTCD` int(10) DEFAULT NULL,
  `TongTCKD` int(10) DEFAULT NULL,
  `KetQua` varchar(255) DEFAULT NULL,
  `IdXuong` varchar(50) NOT NULL,
  `IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_ban_danh_gia_thanh_pham`
--

CREATE TABLE `bien_ban_danh_gia_thanh_pham` (
  `IdBienBanDanhGiaSP` varchar(50) NOT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `TongTCD` int(10) DEFAULT NULL,
  `TongTCKD` int(10) DEFAULT NULL,
  `KetQua` varchar(255) DEFAULT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hinh_nguyen_lieu`
--

CREATE TABLE `cau_hinh_nguyen_lieu` (
  `IdCauHinhNguyenLieu` varchar(50) NOT NULL,
  `IdCauHinh` varchar(50) NOT NULL,
  `IdNguyenLieu` varchar(50) NOT NULL,
  `TyLeSoLuong` float DEFAULT 1,
  `DinhMuc` float DEFAULT NULL,
  `Nhan` varchar(255) DEFAULT NULL,
  `DonVi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hinh_san_pham`
--

CREATE TABLE `cau_hinh_san_pham` (
  `IdCauHinh` varchar(50) NOT NULL,
  `TenCauHinh` varchar(255) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `GiaBan` float DEFAULT NULL,
  `IdSanPham` varchar(50) NOT NULL,
  `IdBOM` varchar(50) NOT NULL,
  `Keycap` varchar(100) DEFAULT NULL,
  `Mainboard` varchar(100) DEFAULT NULL,
  `Layout` varchar(100) DEFAULT NULL,
  `SwitchType` varchar(100) DEFAULT NULL,
  `CaseType` varchar(100) DEFAULT NULL,
  `Foam` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hinh_thong_bao`
--

CREATE TABLE `cau_hinh_thong_bao` (
  `MaCauHinh` varchar(100) NOT NULL,
  `GiaTri` text DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ca_lam`
--

CREATE TABLE `ca_lam` (
  `IdCaLamViec` varchar(50) NOT NULL,
  `TenCa` varchar(255) DEFAULT NULL,
  `LoaiCa` varchar(255) DEFAULT NULL,
  `NgayLamViec` date DEFAULT NULL,
  `ThoiGianBatDau` datetime DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TongSL` int(10) DEFAULT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) DEFAULT NULL,
  `LOIdLo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ca_lam`
--

INSERT INTO `ca_lam` (`IdCaLamViec`, `TenCa`, `LoaiCa`, `NgayLamViec`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TongSL`, `IdKeHoachSanXuatXuong`, `LOIdLo`) VALUES
('CA_FIX_0501_S', 'Ca sáng', 'Cố định', '2023-05-01', '2023-05-01 06:30:00', '2023-05-01 14:00:00', 0, NULL, NULL),
('CA_FIX_0501_T', 'Ca trưa', 'Cố định', '2023-05-01', '2023-05-01 14:00:00', '2023-05-01 22:00:00', 0, NULL, NULL),
('CA_FIX_0501_D', 'Ca tối', 'Cố định', '2023-05-01', '2023-05-01 22:00:00', '2023-05-02 06:00:00', 0, NULL, NULL),
('CA_FIX_0502_S', 'Ca sáng', 'Cố định', '2023-05-02', '2023-05-02 06:30:00', '2023-05-02 14:00:00', 0, NULL, NULL),
('CA_FIX_0502_T', 'Ca trưa', 'Cố định', '2023-05-02', '2023-05-02 14:00:00', '2023-05-02 22:00:00', 0, NULL, NULL),
('CA_FIX_0502_D', 'Ca tối', 'Cố định', '2023-05-02', '2023-05-02 22:00:00', '2023-05-03 06:00:00', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cham_cong`
--

CREATE TABLE `cham_cong` (
  `IdChamCong` varchar(50) NOT NULL,
  `NHANVIEN IdNhanVien` varchar(50) NOT NULL,
  `ThoiGIanRa` datetime DEFAULT NULL,
  `ThoiGianVao` datetime DEFAULT NULL,
  `ViTriVaoLat` decimal(10,6) DEFAULT NULL,
  `ViTriVaoLng` decimal(10,6) DEFAULT NULL,
  `ViTriVaoAccuracy` decimal(10,2) DEFAULT NULL,
  `ViTriRaLat` decimal(10,6) DEFAULT NULL,
  `ViTriRaLng` decimal(10,6) DEFAULT NULL,
  `ViTriRaAccuracy` decimal(10,2) DEFAULT NULL,
  `XUONGTRUONG IdNhanVien` varchar(50) DEFAULT NULL,
  `IdCaLamViec` varchar(50) NOT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cham_cong`
--

INSERT INTO `cham_cong` (`IdChamCong`, `NHANVIEN IdNhanVien`, `ThoiGIanRa`, `ThoiGianVao`, `ViTriVaoLat`, `ViTriVaoLng`, `ViTriVaoAccuracy`, `ViTriRaLat`, `ViTriRaLng`, `ViTriRaAccuracy`, `XUONGTRUONG IdNhanVien`, `IdCaLamViec`, `GhiChu`) VALUES
('CC0001', 'NV009', '2023-05-01 14:00:00', '2023-05-01 06:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV005', 'CA_FIX_0501_S', 'Chấm công mẫu'),
('CC0002', 'NV010', '2023-05-01 14:00:00', '2023-05-01 06:45:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV005', 'CA_FIX_0501_S', 'Chấm công mẫu'),
('CC0003', 'NV011', '2023-05-01 22:00:00', '2023-05-01 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV005', 'CA_FIX_0501_T', 'Chấm công mẫu'),
('CC0004', 'NV012', '2023-05-01 22:00:00', '2023-05-01 14:10:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV008', 'CA_FIX_0501_T', 'Chấm công mẫu'),
('CC0005', 'NV018', '2023-05-02 06:00:00', '2023-05-01 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV005', 'CA_FIX_0501_D', 'Chấm công mẫu'),
('CC0006', 'NV009', '2023-05-02 14:00:00', '2023-05-02 06:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV005', 'CA_FIX_0502_S', 'Chấm công mẫu'),
('CC0007', 'NV013', '2023-05-02 22:00:00', '2023-05-02 14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV008', 'CA_FIX_0502_T', 'Chấm công mẫu'),
('CC0008', 'NV019', '2023-05-03 06:00:00', '2023-05-02 22:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 'NV008', 'CA_FIX_0502_D', 'Chấm công mẫu');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_ke_hoach_san_xuat_xuong`
--

CREATE TABLE `chi_tiet_ke_hoach_san_xuat_xuong` (
  `IdCTKHSXX` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `IdNguyenLieu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_don_hang`
--

CREATE TABLE `ct_don_hang` (
  `IdTTCTDonHang` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `NgayGiao` datetime DEFAULT NULL,
  `YeuCau` text DEFAULT NULL,
  `DonGia` float DEFAULT NULL,
  `ThanhTien` float DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `VAT` float DEFAULT NULL,
  `IdSanPham` varchar(50) NOT NULL,
  `IdCauHinh` varchar(50) NOT NULL,
  `IdDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_hoa_don`
--

CREATE TABLE `ct_hoa_don` (
  `IdCTHoaDon` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThueVAT` int(10) DEFAULT NULL,
  `TongTien` int(10) DEFAULT NULL,
  `PhuongThucTT` varchar(255) DEFAULT NULL,
  `IdHoaDon` varchar(50) NOT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieu`
--

CREATE TABLE `ct_phieu` (
  `IdTTCTPhieu` varchar(50) NOT NULL,
  `DonViTinh` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThucNhan` int(10) DEFAULT NULL,
  `IdPhieu` varchar(50) NOT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `IdDonHang` varchar(50) NOT NULL,
  `YeuCau` text DEFAULT NULL,
  `TongTien` float DEFAULT NULL,
  `NgayLap` date DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `EmailLienHe` varchar(255) DEFAULT NULL,
  `IdKhachHang` varchar(50) NOT NULL,
  `IdNguoiTao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoat_dong_he_thong`
--

CREATE TABLE `hoat_dong_he_thong` (
  `IdHoatDong` varchar(50) NOT NULL,
  `HanhDong` text DEFAULT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `IdNguoiDung` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `IdHoaDon` varchar(50) NOT NULL,
  `NgayLap` date DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `LoaiHD` varchar(255) DEFAULT NULL,
  `IdDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ke_hoach_san_xuat`
--

CREATE TABLE `ke_hoach_san_xuat` (
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `ThoiGianBD` datetime DEFAULT NULL,
  `IdNguoiLap` varchar(50) NOT NULL,
  `IdTTCTDonHang` varchar(50) NOT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ke_hoach_san_xuat_xuong`
--

CREATE TABLE `ke_hoach_san_xuat_xuong` (
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `TenThanhThanhPhanSP` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThoiGianBatDau` datetime DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `TinhTrangVatTu` varchar(255) DEFAULT 'Chưa kiểm tra',
  `IdCongDoan` varchar(50) DEFAULT NULL,
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `IdXuong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

CREATE TABLE `khach_hang` (
  `IdKhachHang` varchar(50) NOT NULL,
  `HoTen` varchar(255) DEFAULT NULL,
  `TenCongTy` varchar(255) DEFAULT NULL,
  `GioiTinh` tinyint(3) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SoLuongDonHang` int(10) DEFAULT NULL,
  `SoDienThoai` varchar(12) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `TongTien` float DEFAULT NULL,
  `LoaiKhachHang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kho`
--

CREATE TABLE `kho` (
  `IdKho` varchar(50) NOT NULL,
  `TenKho` varchar(255) DEFAULT NULL,
  `TenLoaiKho` varchar(255) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `TongSLLo` int(10) DEFAULT NULL,
  `ThanhTien` int(10) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `TongSL` int(10) DEFAULT NULL,
  `IdXuong` varchar(50) NOT NULL,
  `NHAN_VIEN_KHO_IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kho`
--

INSERT INTO `kho` (`IdKho`, `TenKho`, `TenLoaiKho`, `DiaChi`, `TongSLLo`, `ThanhTien`, `TrangThai`, `TongSL`, `IdXuong`, `NHAN_VIEN_KHO_IdNhanVien`) VALUES
('KHO001', 'Kho Bắc Ninh', 'Nguyên liệu', 'Bắc Ninh', 0, 0, 'Đang sử dụng', 0, 'XUONG001', 'NV012'),
('KHO002', 'Kho Hà Nội', 'Nguyên liệu', 'Hà Nội', 0, 0, 'Đang sử dụng', 0, 'XUONG002', 'NV013'),
('KHO003', 'Kho thành phẩm Bắc Ninh', 'Thành phẩm', 'Bắc Ninh', 0, 0, 'Đang sử dụng', 0, 'XUONG001', 'NV012');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_ke_hoach_xuong`
--

CREATE TABLE `lich_su_ke_hoach_xuong` (
  `IdLichSu` varchar(50) NOT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `HanhDong` varchar(255) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `NguoiThucHien` varchar(50) DEFAULT NULL,
  `NgayThucHien` datetime DEFAULT NULL,
  `ThongTinChiTiet` longtext DEFAULT NULL,
  `IdYeuCauKho` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lo`
--

CREATE TABLE `lo` (
  `IdLo` varchar(50) NOT NULL,
  `TenLo` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL,
  `LoaiLo` varchar(255) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `IdSanPham` varchar(50) NOT NULL,
  `IdKho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lo`
--

INSERT INTO `lo` (`IdLo`, `TenLo`, `SoLuong`, `NgayTao`, `LoaiLo`, `TrangThai`, `IdSanPham`, `IdKho`) VALUES
('LO001', 'Lô nguyên liệu BN-01', 1200, '2023-05-01 08:00:00', 'Nguyên liệu', 'Đang lưu kho', 'SP001', 'KHO001'),
('LO002', 'Lô nguyên liệu BN-02', 800, '2023-05-02 08:00:00', 'Nguyên liệu', 'Đang lưu kho', 'SP001', 'KHO001'),
('LO003', 'Lô nguyên liệu HN-01', 5000, '2023-05-01 09:00:00', 'Nguyên liệu', 'Đang lưu kho', 'SP002', 'KHO002'),
('LO004', 'Lô thành phẩm BN-TP-01', 350, '2023-05-05 14:00:00', 'Thành phẩm', 'Đã hoàn thiện', 'SP001', 'KHO003'),
('LO005', 'Lô thành phẩm BN-TP-02', 220, '2023-05-07 15:30:00', 'Thành phẩm', 'Đã hoàn thiện', 'SP002', 'KHO003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `IdNguoiDung` varchar(50) NOT NULL,
  `TenDangNhap` varchar(255) DEFAULT NULL,
  `MatKhau` varchar(255) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `IdNhanVien` varchar(50) NOT NULL,
  `IdVaiTro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`IdNguoiDung`, `TenDangNhap`, `MatKhau`, `TrangThai`, `IdNhanVien`, `IdVaiTro`) VALUES
('ND001', 'user01', '123456', 'Hoạt động', 'NV001', 'VT_ADMIN'),
('ND002', 'user02', '123456', 'Hoạt động', 'NV002', 'VT_BAN_GIAM_DOC'),
('ND003', 'user03', '123456', 'Hoạt động', 'NV003', 'VT_KETOAN'),
('ND004', 'user04', '123456', 'Hoạt động', 'NV004', 'VT_KINH_DOANH'),
('ND005', 'user05', '123456', 'Hoạt động', 'NV005', 'VT_TRUONG_XUONG_SAN_XUAT'),
('ND006', 'user06', '123456', 'Hoạt động', 'NV006', 'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI'),
('ND007', 'user07', '123456', 'Hoạt động', 'NV007', 'VT_TRUONG_XUONG_KIEM_DINH'),
('ND008', 'user08', '123456', 'Hoạt động', 'NV008', 'VT_TRUONG_XUONG_LUU_TRU'),
('ND009', 'user09', '123456', 'Hoạt động', 'NV009', 'VT_NHANVIEN_SANXUAT'),
('ND010', 'user10', '123456', 'Hoạt động', 'NV010', 'VT_NHANVIEN_SANXUAT'),
('ND011', 'user11', '123456', 'Hoạt động', 'NV011', 'VT_NHANVIEN_SANXUAT'),
('ND012', 'user12', '123456', 'Hoạt động', 'NV012', 'VT_NHANVIEN_KHO'),
('ND013', 'user13', '123456', 'Hoạt động', 'NV013', 'VT_NHANVIEN_KHO'),
('ND014', 'user14', '123456', 'Hoạt động', 'NV014', 'VT_KIEM_SOAT_CL'),
('ND015', 'user15', '123456', 'Hoạt động', 'NV015', 'VT_KIEM_SOAT_CL'),
('ND016', 'user16', '123456', 'Hoạt động', 'NV016', 'VT_KINH_DOANH'),
('ND017', 'user17', '123456', 'Hoạt động', 'NV017', 'VT_KINH_DOANH'),
('ND018', 'user18', '123456', 'Hoạt động', 'NV018', 'VT_NHANVIEN_SANXUAT'),
('ND019', 'user19', '123456', 'Hoạt động', 'NV019', 'VT_NHANVIEN_KHO'),
('ND020', 'user20', '123456', 'Hoạt động', 'NV020', 'VT_NHANVIEN_SANXUAT');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguyen_lieu`
--

CREATE TABLE `nguyen_lieu` (
  `IdNguyenLieu` varchar(50) NOT NULL,
  `TenNL` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `DonVi` varchar(50) DEFAULT NULL,
  `DonGian` float DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `NgaySanXuat` datetime DEFAULT NULL,
  `NgayHetHan` datetime DEFAULT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguyen_lieu`
--

INSERT INTO `nguyen_lieu` (`IdNguyenLieu`, `TenNL`, `SoLuong`, `DonVi`, `DonGian`, `TrangThai`, `NgaySanXuat`, `NgayHetHan`, `IdLo`) VALUES
('NL001', 'Cuộn thép 1mm', 1200, 'kg', 18000, 'Còn hàng', '2023-04-15 00:00:00', '2024-04-15 00:00:00', 'LO001'),
('NL002', 'Nhựa ABS', 800, 'kg', 22000, 'Còn hàng', '2023-04-18 00:00:00', '2024-04-18 00:00:00', 'LO002'),
('NL003', 'Bao bì carton', 5000, 'tấm', 3500, 'Còn hàng', '2023-04-20 00:00:00', '2025-04-20 00:00:00', 'LO003'),
('NL004', 'Bộ keycap PBT', 950, 'bộ', 65000, 'Còn hàng', '2023-04-22 00:00:00', '2025-04-22 00:00:00', 'LO002'),
('NL005', 'Switch tuyến tính', 5000, 'cái', 4500, 'Còn hàng', '2023-04-25 00:00:00', '2025-04-25 00:00:00', 'LO003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `IdNhanVien` varchar(50) NOT NULL,
  `HoTen` varchar(255) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` tinyint(3) DEFAULT NULL,
  `ChucVu` varchar(255) DEFAULT NULL,
  `HeSoLuong` int(10) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `ThoiGianLamViec` datetime DEFAULT NULL,
  `ChuKy` varbinary(2000) DEFAULT NULL,
  `idXuong` varchar(50) DEFAULT NULL,
  `IdVaiTro` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`, `IdVaiTro`) VALUES
('NV001', 'Nguyễn Văn An', '1986-04-12', 1, 'Quản trị hệ thống', 4, 'Đang làm việc', 'Hà Nội', '2023-01-05 08:00:00', NULL, NULL, 'VT_ADMIN'),
('NV002', 'Trần Thị Bình', '1980-09-20', 0, 'Giám đốc điều hành', 5, 'Đang làm việc', 'Hà Nội', '2022-11-01 08:00:00', NULL, NULL, 'VT_BAN_GIAM_DOC'),
('NV003', 'Lê Văn Cường', '1988-06-10', 1, 'Kế toán trưởng', 4, 'Đang làm việc', 'Hà Nội', '2023-02-15 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV004', 'Phạm Thị Diệu', '1992-03-08', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'Hà Nội', '2023-04-20 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV005', 'Vũ Văn Dũng', '1985-12-19', 1, 'Xưởng trưởng sản xuất', 4, 'Đang làm việc', 'Bắc Ninh', '2022-12-12 08:00:00', NULL, NULL, 'VT_TRUONG_XUONG_SAN_XUAT'),
('NV006', 'Đặng Thị Hương', '1987-07-14', 0, 'Xưởng trưởng lắp ráp', 4, 'Đang làm việc', 'Bắc Ninh', '2022-12-20 08:00:00', NULL, NULL, 'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI'),
('NV007', 'Hoàng Văn Khánh', '1989-02-11', 1, 'Xưởng trưởng kiểm định', 4, 'Đang làm việc', 'Bắc Ninh', '2023-01-18 08:00:00', NULL, NULL, 'VT_TRUONG_XUONG_KIEM_DINH'),
('NV008', 'Nguyễn Thị Lan', '1990-10-05', 0, 'Xưởng trưởng lưu trữ', 4, 'Đang làm việc', 'Bắc Ninh', '2023-01-25 08:00:00', NULL, NULL, 'VT_TRUONG_XUONG_LUU_TRU'),
('NV009', 'Lê Văn Minh', '1995-08-22', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Bắc Ninh', '2023-03-01 08:00:00', NULL, NULL, 'VT_NHANVIEN_SANXUAT'),
('NV010', 'Phạm Thị Ngọc', '1996-04-18', 0, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Bắc Ninh', '2023-03-05 08:00:00', NULL, NULL, 'VT_NHANVIEN_SANXUAT'),
('NV011', 'Đinh Văn Phúc', '1994-12-01', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Bắc Ninh', '2023-03-08 08:00:00', NULL, NULL, 'VT_NHANVIEN_SANXUAT'),
('NV012', 'Trần Thị Quỳnh', '1993-11-15', 0, 'Nhân viên kho', 2, 'Đang làm việc', 'Hải Phòng', '2023-03-10 08:00:00', NULL, NULL, 'VT_NHANVIEN_KHO'),
('NV013', 'Phan Văn Sơn', '1991-05-09', 1, 'Nhân viên kho', 2, 'Đang làm việc', 'Hải Phòng', '2023-03-12 08:00:00', NULL, NULL, 'VT_NHANVIEN_KHO'),
('NV014', 'Nguyễn Thị Trang', '1992-01-27', 0, 'Nhân viên kiểm soát CL', 3, 'Đang làm việc', 'Hà Nội', '2023-02-02 08:00:00', NULL, NULL, 'VT_KIEM_SOAT_CL'),
('NV015', 'Đỗ Văn Tuấn', '1990-07-30', 1, 'Nhân viên kiểm soát CL', 3, 'Đang làm việc', 'Hà Nội', '2023-02-06 08:00:00', NULL, NULL, 'VT_KIEM_SOAT_CL'),
('NV016', 'Lý Thị Vân', '1994-09-09', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'Hà Nội', '2023-03-15 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV017', 'Ngô Văn Xuyên', '1993-02-02', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'Hà Nội', '2023-03-18 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV018', 'Bùi Thị Yến', '1997-06-21', 0, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Bắc Ninh', '2023-04-01 08:00:00', NULL, NULL, 'VT_NHANVIEN_SANXUAT'),
('NV019', 'Trịnh Văn Zũng', '1996-12-12', 1, 'Nhân viên kho', 2, 'Đang làm việc', 'Hải Phòng', '2023-04-05 08:00:00', NULL, NULL, 'VT_NHANVIEN_KHO'),
('NV020', 'Nguyễn Thị Ánh', '1995-03-03', 0, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Bắc Ninh', '2023-04-10 08:00:00', NULL, NULL, 'VT_NHANVIEN_SANXUAT');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phan_cong_ke_hoach_xuong`
--

CREATE TABLE `phan_cong_ke_hoach_xuong` (
  `IdPhanCong` varchar(50) NOT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `IdNhanVien` varchar(50) NOT NULL,
  `IdCaLamViec` varchar(50) NOT NULL,
  `VaiTro` varchar(50) DEFAULT NULL,
  `NgayPhanCong` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu`
--

CREATE TABLE `phieu` (
  `IdPhieu` varchar(50) NOT NULL,
  `NgayLP` date DEFAULT NULL,
  `NgayXN` date DEFAULT NULL,
  `TongTien` int(10) DEFAULT NULL,
  `LoaiPhieu` varchar(255) DEFAULT NULL,
  `IdKho` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien2` varchar(50) NOT NULL,
  `LoaiDoiTac` varchar(50) DEFAULT NULL,
  `DoiTac` varchar(255) DEFAULT NULL,
  `SoThamChieu` varchar(100) DEFAULT NULL,
  `LyDo` varchar(255) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_components`
--

CREATE TABLE `product_components` (
  `IdBOM` varchar(50) NOT NULL,
  `TenBOM` varchar(255) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `IdSanPham` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `IdSanPham` varchar(50) NOT NULL,
  `TenSanPham` varchar(255) DEFAULT NULL,
  `DonVi` varchar(255) DEFAULT NULL,
  `GiaBan` float DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`IdSanPham`, `TenSanPham`, `DonVi`, `GiaBan`, `MoTa`) VALUES
('SP001', 'Linh kiện tiêu chuẩn A', 'sp', 120000, 'Sản phẩm tiêu chuẩn dùng cho lô nguyên liệu'),
('SP002', 'Linh kiện tiêu chuẩn B', 'sp', 98000, 'Sản phẩm tiêu chuẩn dùng cho kho Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_pham`
--

CREATE TABLE `thanh_pham` (
  `IdThanhPham` varchar(50) NOT NULL,
  `TenThanhPham` varchar(255) DEFAULT NULL,
  `YeuCau` text DEFAULT NULL,
  `DonGia` int(10) DEFAULT NULL,
  `LoaiTP` varchar(255) DEFAULT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanh_pham`
--

INSERT INTO `thanh_pham` (`IdThanhPham`, `TenThanhPham`, `YeuCau`, `DonGia`, `LoaiTP`, `IdLo`) VALUES
('TP001', 'Bàn phím tiêu chuẩn A - bản thương mại', 'QC hoàn tất, đóng gói đầy đủ phụ kiện', 1500000, 'Bàn phím', 'LO004'),
('TP002', 'Bàn phím tiêu chuẩn B - bản thương mại', 'Kiểm định hoàn thành, sẵn sàng xuất kho', 1250000, 'Bàn phím', 'LO005');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--

CREATE TABLE `ttct_bien_ban_danh_gia_dot_xuat` (
  `IdTTCTBBDGDX` varchar(50) NOT NULL,
  `LoaiTieuChi` varchar(255) DEFAULT NULL,
  `TieuChi` varchar(255) DEFAULT NULL,
  `DiemDG` int(10) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `IdBienBanDanhGiaDX` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--

CREATE TABLE `ttct_bien_ban_danh_gia_thanh_pham` (
  `IdTTCTBBDGTP` varchar(50) NOT NULL,
  `Tieuchi` varchar(255) DEFAULT NULL,
  `DiemD` int(10) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `IdBienBanDanhGiaSP` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `IdVaiTro` varchar(50) NOT NULL,
  `TenVaiTro` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vai_tro`
--

INSERT INTO `vai_tro` (`IdVaiTro`, `TenVaiTro`) VALUES
('VT_ADMIN', 'Quản trị hệ thống'),
('VT_BAN_GIAM_DOC', 'Ban giám đốc'),
('VT_TRUONG_XUONG_KIEM_DINH', 'Xưởng trưởng kiểm định'),
('VT_TRUONG_XUONG_LAP_RAP_DONG_GOI', 'Xưởng trưởng lắp ráp và đóng gói'),
('VT_TRUONG_XUONG_SAN_XUAT', 'Xưởng trưởng sản xuất'),
('VT_TRUONG_XUONG_LUU_TRU', 'Xưởng trưởng lưu trữ hàng hóa'),
('VT_KETOAN', 'Kế toán'),
('VT_KHO_TRUONG', 'Kho trưởng'),
('VT_KIEM_SOAT_CL', 'Nhân viên kiểm soát chất lượng'),
('VT_KINH_DOANH', 'Nhân viên kinh doanh'),
('VT_NHANVIEN_KHO', 'Nhân viên kho'),
('VT_NHANVIEN_SANXUAT', 'Nhân viên sản xuất'),
('VT_TRUONG_XUONG_KIEM_DINH', 'Xưởng trưởng kiểm định'),
('VT_TRUONG_XUONG_LAP_RAP_DONG_GOI', 'Xưởng trưởng lắp ráp và đóng gói'),
('VT_TRUONG_XUONG_LUU_TRU', 'Xưởng trưởng lưu trữ hàng hóa'),
('VT_TRUONG_XUONG_SAN_XUAT', 'Xưởng trưởng sản xuất');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xuong`
--

CREATE TABLE `xuong` (
  `IdXuong` varchar(50) NOT NULL,
  `TenXuong` varchar(255) DEFAULT NULL,
  `DiaDiem` varchar(255) DEFAULT NULL,
  `NgayThanhLap` date DEFAULT NULL,
  `SlThietBi` int(10) DEFAULT NULL,
  `SlNhanVien` int(10) DEFAULT NULL,
  `SoLuongCongNhan` int(10) DEFAULT 0,
  `TenQuyTrinh` varchar(255) DEFAULT NULL,
  `CongSuatToiDa` float DEFAULT 0,
  `CongSuatDangSuDung` float DEFAULT NULL,
  `LoaiXuong` varchar(255) DEFAULT 'Sản xuất',
  `TrangThai` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `XUONGTRUONG_IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `xuong`
--

INSERT INTO `xuong` (`IdXuong`, `TenXuong`, `DiaDiem`, `NgayThanhLap`, `SlThietBi`, `SlNhanVien`, `SoLuongCongNhan`, `TenQuyTrinh`, `CongSuatToiDa`, `CongSuatDangSuDung`, `LoaiXuong`, `TrangThai`, `MoTa`, `XUONGTRUONG_IdNhanVien`) VALUES
('XUONG001', 'Xưởng sản xuất Bắc Ninh', 'Bắc Ninh', '2023-01-10', 20, 50, 0, 'Quy trình sản xuất chuẩn', 1000, 0, 'Sản xuất', 'Đang hoạt động', 'Xưởng sản xuất chính', 'NV005'),
('XUONG002', 'Xưởng lắp ráp Hà Nội', 'Hà Nội', '2023-02-05', 15, 30, 0, 'Quy trình lắp ráp', 600, 0, 'Sản xuất', 'Đang hoạt động', 'Xưởng lắp ráp và đóng gói', 'NV006');

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xuong_nhan_vien`
--

CREATE TABLE `xuong_nhan_vien` (
  `IdXuong` varchar(50) NOT NULL,
  `IdNhanVien` varchar(50) NOT NULL,
  `VaiTro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `xuong_nhan_vien`
--

-- (Dữ liệu đã được lược bỏ)

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xuong_cau_hinh_san_pham`
--

CREATE TABLE `xuong_cau_hinh_san_pham` (
  `IdPhanCong` varchar(50) NOT NULL,
  `IdSanPham` varchar(50) DEFAULT NULL,
  `IdCauHinh` varchar(50) DEFAULT NULL,
  `IdXuong` varchar(50) NOT NULL,
  `TenPhanCong` varchar(255) DEFAULT NULL,
  `TyLeSoLuong` float DEFAULT 1,
  `DonVi` varchar(50) DEFAULT 'sp',
  `TrangThaiMacDinh` varchar(255) DEFAULT NULL,
  `LogisticsKey` varchar(50) DEFAULT NULL,
  `LogisticsLabel` varchar(255) DEFAULT NULL,
  `IncludeYeuCau` tinyint(1) DEFAULT 0,
  `ThuTu` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeu_cau_xuat_kho`
--

CREATE TABLE `yeu_cau_xuat_kho` (
  `IdYeuCau` varchar(50) NOT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `NguoiYeuCau` varchar(50) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bang_luong`
--
ALTER TABLE `bang_luong`
  ADD PRIMARY KEY (`IdBangLuong`),
  ADD KEY `Ke toan` (`NHAN_VIENIdNhanVien`),
  ADD KEY `Nhan vien co bang luong` (`KETOAN IdNhanVien2`);

--
-- Chỉ mục cho bảng `bien_ban_danh_gia_dot_xuat`
--
ALTER TABLE `bien_ban_danh_gia_dot_xuat`
  ADD PRIMARY KEY (`IdBienBanDanhGiaDX`),
  ADD KEY `FKBIEN_BAN_D429844` (`IdXuong`),
  ADD KEY `Nhan vien kiem soat chat luong` (`IdNhanVien`);

--
-- Chỉ mục cho bảng `bien_ban_danh_gia_thanh_pham`
--
ALTER TABLE `bien_ban_danh_gia_thanh_pham`
  ADD PRIMARY KEY (`IdBienBanDanhGiaSP`),
  ADD KEY `FKBIEN_BAN_D733684` (`IdLo`);

--
-- Chỉ mục cho bảng `cau_hinh_nguyen_lieu`
--
ALTER TABLE `cau_hinh_nguyen_lieu`
  ADD KEY `FKCFGNL_CAU_HINH` (`IdCauHinh`),
  ADD KEY `FKCFGNL_NGUYEN_LIEU` (`IdNguyenLieu`);

--
-- Chỉ mục cho bảng `cau_hinh_san_pham`
--
ALTER TABLE `cau_hinh_san_pham`
  ADD PRIMARY KEY (`IdCauHinh`),
  ADD KEY `FKCAU_HINH_SANPHAM` (`IdSanPham`),
  ADD KEY `FKCAU_HINH_BOM` (`IdBOM`);

--
-- Chỉ mục cho bảng `cau_hinh_thong_bao`
--
ALTER TABLE `cau_hinh_thong_bao`
  ADD PRIMARY KEY (`MaCauHinh`);

--
-- Chỉ mục cho bảng `ca_lam`
--
ALTER TABLE `ca_lam`
  ADD PRIMARY KEY (`IdCaLamViec`),
  ADD KEY `FKCA_LAM945015` (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKCA_LAM557411` (`LOIdLo`);

--
-- Chỉ mục cho bảng `cham_cong`
--
ALTER TABLE `cham_cong`
  ADD PRIMARY KEY (`IdChamCong`),
  ADD KEY `Nhan vien duoc cham cong` (`NHANVIEN IdNhanVien`),
  ADD KEY `Nhan vien cham cong` (`XUONGTRUONG IdNhanVien`),
  ADD KEY `FKCHAM_CONG958641` (`IdCaLamViec`);

--
-- Chỉ mục cho bảng `chi_tiet_ke_hoach_san_xuat_xuong`
--
ALTER TABLE `chi_tiet_ke_hoach_san_xuat_xuong`
  ADD PRIMARY KEY (`IdCTKHSXX`),
  ADD KEY `FKCHI_TIET_K933945` (`IdNguyenLieu`),
  ADD KEY `FKCHI_TIET_K954837` (`IdKeHoachSanXuatXuong`);

--
-- Chỉ mục cho bảng `ct_don_hang`
--
ALTER TABLE `ct_don_hang`
  ADD PRIMARY KEY (`IdTTCTDonHang`),
  ADD KEY `FKCT_DON_HAN864902` (`IdSanPham`),
  ADD KEY `FKCT_DON_HAN_CFG` (`IdCauHinh`),
  ADD KEY `FKCT_DON_HAN479798` (`IdDonHang`);

--
-- Chỉ mục cho bảng `ct_hoa_don`
--
ALTER TABLE `ct_hoa_don`
  ADD PRIMARY KEY (`IdCTHoaDon`),
  ADD KEY `FKCT_HOA_DON878731` (`IdHoaDon`),
  ADD KEY `FKCT_HOA_DON632652` (`IdLo`);

--
-- Chỉ mục cho bảng `ct_phieu`
--
ALTER TABLE `ct_phieu`
  ADD PRIMARY KEY (`IdTTCTPhieu`),
  ADD KEY `FKCT_PHIEU378026` (`IdPhieu`),
  ADD KEY `FKCT_PHIEU491583` (`IdLo`);

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`IdDonHang`),
  ADD KEY `FKDON_HANG579482` (`IdKhachHang`);

--
-- Chỉ mục cho bảng `hoat_dong_he_thong`
--
ALTER TABLE `hoat_dong_he_thong`
  ADD PRIMARY KEY (`IdHoatDong`),
  ADD KEY `FKHOAT_DONG_87294` (`IdNguoiDung`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`IdHoaDon`),
  ADD KEY `FKHOA_DON917821` (`IdDonHang`);

--
-- Chỉ mục cho bảng `ke_hoach_san_xuat`
--
ALTER TABLE `ke_hoach_san_xuat`
  ADD PRIMARY KEY (`IdKeHoachSanXuat`),
  ADD KEY `FKKE_HOACH_S473207` (`IdTTCTDonHang`),
  ADD KEY `FKKE_HOACH_SNGUOILAP` (`IdNguoiLap`);

--
-- Chỉ mục cho bảng `ke_hoach_san_xuat_xuong`
--
ALTER TABLE `ke_hoach_san_xuat_xuong`
  ADD PRIMARY KEY (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKKE_HOACH_S948077` (`IdXuong`),
  ADD KEY `FKKE_HOACH_S948390` (`IdKeHoachSanXuat`),
  ADD KEY `FKKE_HOACH_CONG_DOAN` (`IdCongDoan`);

--
-- Chỉ mục cho bảng `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`IdKhachHang`);

--
-- Chỉ mục cho bảng `kho`
--
ALTER TABLE `kho`
  ADD PRIMARY KEY (`IdKho`),
  ADD KEY `Nhan vien kho` (`NHAN_VIEN_KHO_IdNhanVien`),
  ADD KEY `FKKHO901694` (`IdXuong`);

--
-- Chỉ mục cho bảng `lich_su_ke_hoach_xuong`
--
ALTER TABLE `lich_su_ke_hoach_xuong`
  ADD PRIMARY KEY (`IdLichSu`),
  ADD KEY `FKLSKHX_KeHoach` (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKLSKHX_NhanVien` (`NguoiThucHien`),
  ADD KEY `FKLSKHX_YeuCauKho` (`IdYeuCauKho`);

--
-- Chỉ mục cho bảng `lo`
--
ALTER TABLE `lo`
  ADD PRIMARY KEY (`IdLo`),
  ADD KEY `FKLO20048` (`IdKho`),
  ADD KEY `FKLO159940` (`IdSanPham`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`IdNguoiDung`),
  ADD KEY `FKNGUOI_DUNG977062` (`IdVaiTro`),
  ADD KEY `FKNGUOI_DUNG547019` (`IdNhanVien`);

--
-- Chỉ mục cho bảng `nguyen_lieu`
--
ALTER TABLE `nguyen_lieu`
  ADD PRIMARY KEY (`IdNguyenLieu`),
  ADD KEY `FKNGUYEN_LIE587750` (`IdLo`);

--
-- Chỉ mục cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`IdNhanVien`),
  ADD KEY `idXuong` (`idXuong`),
  ADD KEY `FKNHAN_VIEN_VAI_TRO` (`IdVaiTro`);

--
-- Chỉ mục cho bảng `phan_cong_ke_hoach_xuong`
--
ALTER TABLE `phan_cong_ke_hoach_xuong`
  ADD PRIMARY KEY (`IdPhanCong`),
  ADD KEY `FKPCKHX_KeHoach` (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKPCKHX_NhanVien` (`IdNhanVien`),
  ADD KEY `FKPCKHX_CaLam` (`IdCaLamViec`);

--
-- Chỉ mục cho bảng `phieu`
--
ALTER TABLE `phieu`
  ADD PRIMARY KEY (`IdPhieu`),
  ADD KEY `FKPHIEU116698` (`IdKho`),
  ADD KEY `Nhan vien lap phieu` (`NHAN_VIENIdNhanVien`),
  ADD KEY `Nhan vien xac nhan phieu` (`NHAN_VIENIdNhanVien2`);

--
-- Chỉ mục cho bảng `product_components`
--
ALTER TABLE `product_components`
  ADD PRIMARY KEY (`IdBOM`),
  ADD KEY `FK_BOM_SANPHAM` (`IdSanPham`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`IdSanPham`);

--
-- Chỉ mục cho bảng `thanh_pham`
--
ALTER TABLE `thanh_pham`
  ADD PRIMARY KEY (`IdThanhPham`),
  ADD KEY `FKTHANH_PHAM566175` (`IdLo`);

--
-- Chỉ mục cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--
ALTER TABLE `ttct_bien_ban_danh_gia_dot_xuat`
  ADD PRIMARY KEY (`IdTTCTBBDGDX`),
  ADD KEY `FKTTCT_BIEN_760467` (`IdBienBanDanhGiaDX`);

--
-- Chỉ mục cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--
ALTER TABLE `ttct_bien_ban_danh_gia_thanh_pham`
  ADD PRIMARY KEY (`IdTTCTBBDGTP`),
  ADD KEY `FKTTCT_BIEN_864028` (`IdBienBanDanhGiaSP`);

--
-- Chỉ mục cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`IdVaiTro`);

--
-- Chỉ mục cho bảng `xuong`
--
ALTER TABLE `xuong`
  ADD PRIMARY KEY (`IdXuong`),
  ADD KEY `Xuong truong` (`XUONGTRUONG_IdNhanVien`);

--
-- Chỉ mục cho bảng `xuong_cau_hinh_san_pham`
--
ALTER TABLE `xuong_cau_hinh_san_pham`
  ADD PRIMARY KEY (`IdPhanCong`),
  ADD KEY `FKXCHSP_SANPHAM` (`IdSanPham`),
  ADD KEY `FKXCHSP_CAU_HINH` (`IdCauHinh`),
  ADD KEY `FKXCHSP_XUONG` (`IdXuong`);

--
-- Chỉ mục cho bảng `xuong_nhan_vien`
--
ALTER TABLE `xuong_nhan_vien`
  ADD PRIMARY KEY (`IdXuong`,`IdNhanVien`,`VaiTro`),
  ADD KEY `FKXNV_NhanVien` (`IdNhanVien`);

--
-- Chỉ mục cho bảng `yeu_cau_xuat_kho`
--
ALTER TABLE `yeu_cau_xuat_kho`
  ADD PRIMARY KEY (`IdYeuCau`),
  ADD KEY `FKYCK_KeHoachXuong` (`IdKeHoachSanXuatXuong`);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bang_luong`
--
ALTER TABLE `bang_luong`
  ADD CONSTRAINT `Ke toan` FOREIGN KEY (`NHAN_VIENIdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `Nhan vien co bang luong` FOREIGN KEY (`KETOAN IdNhanVien2`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `bien_ban_danh_gia_dot_xuat`
--
ALTER TABLE `bien_ban_danh_gia_dot_xuat`
  ADD CONSTRAINT `FKBIEN_BAN_D429844` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `Nhan vien kiem soat chat luong` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `bien_ban_danh_gia_thanh_pham`
--
ALTER TABLE `bien_ban_danh_gia_thanh_pham`
  ADD CONSTRAINT `FKBIEN_BAN_D733684` FOREIGN KEY (`IdLo`) REFERENCES `lo` (`IdLo`);

--
-- Các ràng buộc cho bảng `cau_hinh_nguyen_lieu`
--
ALTER TABLE `cau_hinh_nguyen_lieu`
  ADD CONSTRAINT `FKCFGNL_CAU_HINH` FOREIGN KEY (`IdCauHinh`) REFERENCES `cau_hinh_san_pham` (`IdCauHinh`),
  ADD CONSTRAINT `FKCFGNL_NGUYEN_LIEU` FOREIGN KEY (`IdNguyenLieu`) REFERENCES `nguyen_lieu` (`IdNguyenLieu`);

--
-- Các ràng buộc cho bảng `cau_hinh_san_pham`
--
ALTER TABLE `cau_hinh_san_pham`
  ADD CONSTRAINT `FKCAU_HINH_BOM` FOREIGN KEY (`IdBOM`) REFERENCES `product_components` (`IdBOM`),
  ADD CONSTRAINT `FKCAU_HINH_SANPHAM` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`);

--
-- Các ràng buộc cho bảng `ca_lam`
--
ALTER TABLE `ca_lam`
  ADD CONSTRAINT `FKCA_LAM557411` FOREIGN KEY (`LOIdLo`) REFERENCES `lo` (`IdLo`),
  ADD CONSTRAINT `FKCA_LAM945015` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`);

--
-- Các ràng buộc cho bảng `cham_cong`
--
ALTER TABLE `cham_cong`
  ADD CONSTRAINT `FKCHAM_CONG958641` FOREIGN KEY (`IdCaLamViec`) REFERENCES `ca_lam` (`IdCaLamViec`),
  ADD CONSTRAINT `Nhan vien cham cong` FOREIGN KEY (`XUONGTRUONG IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `Nhan vien duoc cham cong` FOREIGN KEY (`NHANVIEN IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `chi_tiet_ke_hoach_san_xuat_xuong`
--
ALTER TABLE `chi_tiet_ke_hoach_san_xuat_xuong`
  ADD CONSTRAINT `FKCHI_TIET_K933945` FOREIGN KEY (`IdNguyenLieu`) REFERENCES `nguyen_lieu` (`IdNguyenLieu`),
  ADD CONSTRAINT `FKCHI_TIET_K954837` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`);

--
-- Các ràng buộc cho bảng `ct_don_hang`
--
ALTER TABLE `ct_don_hang`
  ADD CONSTRAINT `FKCT_DON_HAN479798` FOREIGN KEY (`IdDonHang`) REFERENCES `don_hang` (`IdDonHang`),
  ADD CONSTRAINT `FKCT_DON_HAN864902` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`),
  ADD CONSTRAINT `FKCT_DON_HAN_CFG` FOREIGN KEY (`IdCauHinh`) REFERENCES `cau_hinh_san_pham` (`IdCauHinh`);

--
-- Các ràng buộc cho bảng `ct_hoa_don`
--
ALTER TABLE `ct_hoa_don`
  ADD CONSTRAINT `FKCT_HOA_DON632652` FOREIGN KEY (`IdLo`) REFERENCES `lo` (`IdLo`),
  ADD CONSTRAINT `FKCT_HOA_DON878731` FOREIGN KEY (`IdHoaDon`) REFERENCES `hoa_don` (`IdHoaDon`);

--
-- Các ràng buộc cho bảng `ct_phieu`
--
ALTER TABLE `ct_phieu`
  ADD CONSTRAINT `FKCT_PHIEU378026` FOREIGN KEY (`IdPhieu`) REFERENCES `phieu` (`IdPhieu`),
  ADD CONSTRAINT `FKCT_PHIEU491583` FOREIGN KEY (`IdLo`) REFERENCES `lo` (`IdLo`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `FKDON_HANG579482` FOREIGN KEY (`IdKhachHang`) REFERENCES `khach_hang` (`IdKhachHang`);

--
-- Các ràng buộc cho bảng `hoat_dong_he_thong`
--
ALTER TABLE `hoat_dong_he_thong`
  ADD CONSTRAINT `FKHOAT_DONG_87294` FOREIGN KEY (`IdNguoiDung`) REFERENCES `nguoi_dung` (`IdNguoiDung`);

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `FKHOA_DON917821` FOREIGN KEY (`IdDonHang`) REFERENCES `don_hang` (`IdDonHang`);

--
-- Các ràng buộc cho bảng `ke_hoach_san_xuat`
--
ALTER TABLE `ke_hoach_san_xuat`
  ADD CONSTRAINT `FKKE_HOACH_S473207` FOREIGN KEY (`IdTTCTDonHang`) REFERENCES `ct_don_hang` (`IdTTCTDonHang`),
  ADD CONSTRAINT `FKKE_HOACH_SNGUOILAP` FOREIGN KEY (`IdNguoiLap`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `ke_hoach_san_xuat_xuong`
--
ALTER TABLE `ke_hoach_san_xuat_xuong`
  ADD CONSTRAINT `FKKE_HOACH_S948077` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `FKKE_HOACH_S948390` FOREIGN KEY (`IdKeHoachSanXuat`) REFERENCES `ke_hoach_san_xuat` (`IdKeHoachSanXuat`),
  ADD CONSTRAINT `FKKE_HOACH_XCHSP` FOREIGN KEY (`IdCongDoan`) REFERENCES `xuong_cau_hinh_san_pham` (`IdPhanCong`);

--
-- Các ràng buộc cho bảng `kho`
--
ALTER TABLE `kho`
  ADD CONSTRAINT `FKKHO901694` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `Nhan vien kho` FOREIGN KEY (`NHAN_VIEN_KHO_IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `lich_su_ke_hoach_xuong`
--
ALTER TABLE `lich_su_ke_hoach_xuong`
  ADD CONSTRAINT `FKLSKHX_KeHoach` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`),
  ADD CONSTRAINT `FKLSKHX_NhanVien` FOREIGN KEY (`NguoiThucHien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `FKLSKHX_YeuCauKho` FOREIGN KEY (`IdYeuCauKho`) REFERENCES `yeu_cau_xuat_kho` (`IdYeuCau`);

--
-- Các ràng buộc cho bảng `lo`
--
ALTER TABLE `lo`
  ADD CONSTRAINT `FKLO159940` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`),
  ADD CONSTRAINT `FKLO20048` FOREIGN KEY (`IdKho`) REFERENCES `kho` (`IdKho`);

--
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `FKNGUOI_DUNG547019` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `FKNGUOI_DUNG977062` FOREIGN KEY (`IdVaiTro`) REFERENCES `vai_tro` (`IdVaiTro`);

--
-- Các ràng buộc cho bảng `nguyen_lieu`
--
ALTER TABLE `nguyen_lieu`
  ADD CONSTRAINT `FKNGUYEN_LIE587750` FOREIGN KEY (`IdLo`) REFERENCES `lo` (`IdLo`);

--
-- Các ràng buộc cho bảng `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `FKNHAN_VIEN_VAI_TRO` FOREIGN KEY (`IdVaiTro`) REFERENCES `vai_tro` (`IdVaiTro`),
  ADD CONSTRAINT `nhan_vien_ibfk_1` FOREIGN KEY (`idXuong`) REFERENCES `xuong` (`IdXuong`);

--
-- Các ràng buộc cho bảng `phan_cong_ke_hoach_xuong`
--
ALTER TABLE `phan_cong_ke_hoach_xuong`
  ADD CONSTRAINT `FKPCKHX_CaLam` FOREIGN KEY (`IdCaLamViec`) REFERENCES `ca_lam` (`IdCaLamViec`),
  ADD CONSTRAINT `FKPCKHX_KeHoach` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`),
  ADD CONSTRAINT `FKPCKHX_NhanVien` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `phieu`
--
ALTER TABLE `phieu`
  ADD CONSTRAINT `FKPHIEU116698` FOREIGN KEY (`IdKho`) REFERENCES `kho` (`IdKho`),
  ADD CONSTRAINT `Nhan vien lap phieu` FOREIGN KEY (`NHAN_VIENIdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `Nhan vien xac nhan phieu` FOREIGN KEY (`NHAN_VIENIdNhanVien2`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `thanh_pham`
--
ALTER TABLE `thanh_pham`
  ADD CONSTRAINT `FKTHANH_PHAM566175` FOREIGN KEY (`IdLo`) REFERENCES `lo` (`IdLo`);

--
-- Các ràng buộc cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--
ALTER TABLE `ttct_bien_ban_danh_gia_dot_xuat`
  ADD CONSTRAINT `FKTTCT_BIEN_760467` FOREIGN KEY (`IdBienBanDanhGiaDX`) REFERENCES `bien_ban_danh_gia_dot_xuat` (`IdBienBanDanhGiaDX`);

--
-- Các ràng buộc cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--
ALTER TABLE `ttct_bien_ban_danh_gia_thanh_pham`
  ADD CONSTRAINT `FKTTCT_BIEN_864028` FOREIGN KEY (`IdBienBanDanhGiaSP`) REFERENCES `bien_ban_danh_gia_thanh_pham` (`IdBienBanDanhGiaSP`);

--
-- Các ràng buộc cho bảng `xuong`
--
ALTER TABLE `xuong`
  ADD CONSTRAINT `Xuong truong` FOREIGN KEY (`XUONGTRUONG_IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `xuong_cau_hinh_san_pham`
--
ALTER TABLE `xuong_cau_hinh_san_pham`
  ADD CONSTRAINT `FKXCHSP_CAU_HINH` FOREIGN KEY (`IdCauHinh`) REFERENCES `cau_hinh_san_pham` (`IdCauHinh`),
  ADD CONSTRAINT `FKXCHSP_SANPHAM` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`),
  ADD CONSTRAINT `FKXCHSP_XUONG` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`);

--
-- Các ràng buộc cho bảng `xuong_nhan_vien`
--
ALTER TABLE `xuong_nhan_vien`
  ADD CONSTRAINT `FKXNV_NhanVien` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `FKXNV_Xuong` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`);

--
-- Các ràng buộc cho bảng `yeu_cau_xuat_kho`
--
ALTER TABLE `yeu_cau_xuat_kho`
  ADD CONSTRAINT `FKYCK_KeHoachXuong` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
