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
CREATE DATABASE IF NOT EXISTS `422000422702-quanlysanxuat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `422000422702-quanlysanxuat`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_luong`
--

DROP TABLE IF EXISTS `bang_luong`;

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

--
-- Đang đổ dữ liệu cho bảng `bang_luong`
--

INSERT INTO `bang_luong` (`IdBangLuong`, `KETOAN IdNhanVien2`, `NHAN_VIENIdNhanVien`, `ThangNam`, `LuongCoBan`, `PhuCap`, `DonGiaNgayCong`, `SoNgayCong`, `TongLuongNgayCong`, `Thuong`, `KhauTru`, `TongBaoHiem`, `ThueTNCN`, `TongThuNhap`, `TrangThai`, `NgayLap`, `ChuKy`) VALUES
('BL202311NV003', 'NV006', 'NV003', 202311, 8500000, 1200000, 350000, 22, 7700000, 500000, 500000, 500000, 700000, 16700000, 'Chờ duyệt', '2023-11-28', 'admin.minh'),
('BL202311NV004', 'NV006', 'NV004', 202311, 9000000, 1000000, 380000, 21.5, 8170000, 450000, 600000, 600000, 750000, 17270000, 'Chờ duyệt', '2023-11-28', 'admin.minh'),
('BL202311NV005', 'NV006', 'NV005', 202311, 9500000, 1300000, 400000, 23, 9200000, 600000, 650000, 650000, 780000, 19170000, 'Chờ duyệt', '2023-11-28', 'admin.minh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_ban_danh_gia_dot_xuat`
--

DROP TABLE IF EXISTS `bien_ban_danh_gia_dot_xuat`;

CREATE TABLE `bien_ban_danh_gia_dot_xuat` (
  `IdBienBanDanhGiaDX` varchar(50) NOT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `TongTCD` int(10) DEFAULT NULL,
  `TongTCKD` int(10) DEFAULT NULL,
  `KetQua` varchar(255) DEFAULT NULL,
  `IdXuong` varchar(50) NOT NULL,
  `IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_ban_danh_gia_dot_xuat`
--

INSERT INTO `bien_ban_danh_gia_dot_xuat` (`IdBienBanDanhGiaDX`, `ThoiGian`, `TongTCD`, `TongTCKD`, `KetQua`, `IdXuong`, `IdNhanVien`) VALUES
('BBDX20231101', '2023-11-14 10:00:00', 92, 8, 'Đạt chuẩn ESD', 'XU001', 'NV001'),
('BBDX20231102', '2023-11-20 09:00:00', 90, 10, 'Đạt yêu cầu kiểm thử', 'XU002', 'NV002');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_ban_danh_gia_thanh_pham`
--

DROP TABLE IF EXISTS `bien_ban_danh_gia_thanh_pham`;

CREATE TABLE `bien_ban_danh_gia_thanh_pham` (
  `IdBienBanDanhGiaSP` varchar(50) NOT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `TongTCD` int(10) DEFAULT NULL,
  `TongTCKD` int(10) DEFAULT NULL,
  `KetQua` varchar(255) DEFAULT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_ban_danh_gia_thanh_pham`
--

INSERT INTO `bien_ban_danh_gia_thanh_pham` (`IdBienBanDanhGiaSP`, `ThoiGian`, `TongTCD`, `TongTCKD`, `KetQua`, `IdLo`) VALUES
('BBTP20231101', '2023-11-18 09:30:00', 95, 5, 'Đạt', 'LOTP202309'),
('BBTP20231102', '2023-11-24 14:00:00', 93, 7, 'Đạt', 'LOTP202310'),
('BBTP20231103', '2023-11-28 10:15:00', 91, 9, 'Đạt có điều kiện', 'LOTP202311');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ca_lam`
--

DROP TABLE IF EXISTS `ca_lam`;

CREATE TABLE `ca_lam` (
  `IdCaLamViec` varchar(50) NOT NULL,
  `TenCa` varchar(255) DEFAULT NULL,
  `LoaiCa` varchar(255) DEFAULT NULL,
  `NgayLamViec` date DEFAULT NULL,
  `ThoiGianBatDau` datetime DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TongSL` int(10) DEFAULT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `LOIdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ca_lam`
--

INSERT INTO `ca_lam` (`IdCaLamViec`, `TenCa`, `LoaiCa`, `NgayLamViec`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TongSL`, `IdKeHoachSanXuatXuong`, `LOIdLo`) VALUES
('CA202311S1', 'Ca sáng lắp switch SV5TOT 87', 'Lắp ráp', '2023-11-11', '2023-11-11 07:30:00', '2023-11-11 15:30:00', 35, 'KHSXX202311A', 'LOSW202309'),
('CA202311S2', 'Ca tối kiểm tra PCB SV5TOT', 'Kiểm thử', '2023-11-13', '2023-11-13 14:00:00', '2023-11-13 22:00:00', 28, 'KHSXX202311C', 'LOPCB202310'),
('CA202311S3', 'Ca đêm hoàn thiện SV5TOT 87', 'Hoàn thiện', '2023-11-16', '2023-11-16 21:00:00', '2023-11-17 05:00:00', 24, 'KHSXX202311B', 'LOTP202309'),
('CA202312S1', 'Ca sáng lắp ráp SV5TOT 108', 'Lắp ráp', '2023-12-12', '2023-12-12 07:30:00', '2023-12-12 15:30:00', 30, 'KHSXX202312A', 'LOTP202310');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cham_cong`
--

DROP TABLE IF EXISTS `cham_cong`;

CREATE TABLE `cham_cong` (
  `IdChamCong` varchar(50) NOT NULL,
  `NHANVIEN IdNhanVien` varchar(50) NOT NULL,
  `ThoiGIanRa` datetime DEFAULT NULL,
  `ThoiGianVao` datetime DEFAULT NULL,
  `XUONGTRUONG IdNhanVien` varchar(50) NOT NULL,
  `IdCaLamViec` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cham_cong`
--

INSERT INTO `cham_cong` (`IdChamCong`, `NHANVIEN IdNhanVien`, `ThoiGIanRa`, `ThoiGianVao`, `XUONGTRUONG IdNhanVien`, `IdCaLamViec`) VALUES
('CC2023111101', 'NV003', '2023-11-11 15:45:00', '2023-11-11 07:25:00', 'NV001', 'CA202311S1'),
('CC2023111102', 'NV002', '2023-11-11 16:00:00', '2023-11-11 07:20:00', 'NV001', 'CA202311S1'),
('CC2023111301', 'NV005', '2023-11-13 22:05:00', '2023-11-13 13:55:00', 'NV002', 'CA202311S2'),
('CC2023111601', 'NV005', '2023-11-17 05:10:00', '2023-11-16 20:55:00', 'NV002', 'CA202311S3'),
('CC2023121201', 'NV004', '2023-12-12 15:40:00', '2023-12-12 07:25:00', 'NV001', 'CA202312S1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_ke_hoach_san_xuat_xuong`
--

DROP TABLE IF EXISTS `chi_tiet_ke_hoach_san_xuat_xuong`;

CREATE TABLE `chi_tiet_ke_hoach_san_xuat_xuong` (
  `IdCTKHSXX` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `IdNguyenLieu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_ke_hoach_san_xuat_xuong`
--

INSERT INTO `chi_tiet_ke_hoach_san_xuat_xuong` (`IdCTKHSXX`, `SoLuong`, `IdKeHoachSanXuatXuong`, `IdNguyenLieu`) VALUES
('CTKHSXX202311A', 15660, 'KHSXX202311A', 'NL001'),
('CTKHSXX202311B', 180, 'KHSXX202311A', 'NL002'),
('CTKHSXX202311C', 180, 'KHSXX202311B', 'NL003'),
('CTKHSXX202311D', 120, 'KHSXX202311C', 'NL002'),
('CTKHSXX202311E', 13050, 'KHSXX202311D', 'NL001'),
('CTKHSXX202312A', 160, 'KHSXX202312A', 'NL003'),
('CTKHSXX202312B', 140, 'KHSXX202312B', 'NL002');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_don_hang`
--

DROP TABLE IF EXISTS `ct_don_hang`;

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
  `IdDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_don_hang`
--

INSERT INTO `ct_don_hang` (`IdTTCTDonHang`, `SoLuong`, `NgayGiao`, `YeuCau`, `DonGia`, `ThanhTien`, `GhiChu`, `VAT`, `IdSanPham`, `IdDonHang`) VALUES
('CTDH20231101A', 180, '2023-11-25 09:00:00', 'Lắp sẵn switch Lotus và lube stab', 2450000, 441000000, 'Đóng gói pallet chống sốc', 0.08, 'SPKB87', 'DH20231101'),
('CTDH20231101B', 120, '2023-11-26 13:30:00', 'Thay keycap Glacier theo brand', 2680000, 321600000, 'Ưu tiên kiểm tra RGB 100%', 0.08, 'SPKB108', 'DH20231101'),
('CTDH20231105A', 150, '2023-11-28 08:00:00', 'Tuỳ chỉnh foam và tape mod', 2450000, 367500000, 'Đóng gói theo từng bộ gear', 0.08, 'SPKB87', 'DH20231105'),
('CTDH20231105B', 90, '2023-11-29 14:00:00', 'Đóng gói kèm bộ keycap bổ sung', 1950000, 175500000, 'Đính kèm checklist bảo hành', 0.08, 'SPKBCUSTOM', 'DH20231105'),
('CTDH20231202A', 140, '2023-12-20 10:00:00', 'In logo TechHub lên plate', 2680000, 375200000, 'Giao buổi sáng bằng xe kín', 0.08, 'SPKB108', 'DH20231202'),
('CTDH20231202B', 160, '2023-12-22 15:00:00', 'Chuẩn bị kit custom phối màu riêng', 1950000, 312000000, 'Yêu cầu kèm hướng dẫn dựng phím', 0.08, 'SPKBCUSTOM', 'DH20231202');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_hoa_don`
--

DROP TABLE IF EXISTS `ct_hoa_don`;

CREATE TABLE `ct_hoa_don` (
  `IdCTHoaDon` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThueVAT` int(10) DEFAULT NULL,
  `TongTien` int(10) DEFAULT NULL,
  `PhuongThucTT` varchar(255) DEFAULT NULL,
  `IdHoaDon` varchar(50) NOT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_hoa_don`
--

INSERT INTO `ct_hoa_don` (`IdCTHoaDon`, `SoLuong`, `ThueVAT`, `TongTien`, `PhuongThucTT`, `IdHoaDon`, `IdLo`) VALUES
('CTHD20231101A', 180, 8, 476280000, 'Chuyển khoản', 'HD20231101', 'LOTP202309'),
('CTHD20231101B', 120, 8, 347328000, 'Chuyển khoản', 'HD20231101', 'LOTP202310'),
('CTHD20231105A', 150, 8, 396900000, 'Chuyển khoản', 'HD20231105', 'LOTP202309'),
('CTHD20231105B', 90, 8, 189540000, 'Chuyển khoản', 'HD20231105', 'LOTP202311'),
('CTHD20231202A', 140, 8, 405216000, 'Chuyển khoản', 'HD20231202', 'LOTP202310'),
('CTHD20231202B', 160, 8, 336960000, 'Chuyển khoản', 'HD20231202', 'LOTP202311');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_phieu`
--

DROP TABLE IF EXISTS `ct_phieu`;

CREATE TABLE `ct_phieu` (
  `IdTTCTPhieu` varchar(50) NOT NULL,
  `DonViTinh` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThucNhan` int(10) DEFAULT NULL,
  `IdPhieu` varchar(50) NOT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_phieu`
--

INSERT INTO `ct_phieu` (`IdTTCTPhieu`, `DonViTinh`, `SoLuong`, `ThucNhan`, `IdPhieu`, `IdLo`) VALUES
('CTP20231101A', 'Hộp', 400, 400, 'PN20231101', 'LOSW202309'),
('CTP20231101B', 'Tấm', 200, 200, 'PN20231101', 'LOPCB202310'),
('CTP20231102A', 'Hộp', 250, 245, 'PX20231102', 'LOSW202309'),
('CTP20231102B', 'Tấm', 180, 180, 'PX20231102', 'LOPCB202310'),
('CTP20231103A', 'Bộ', 210, 210, 'PX20231103', 'LOTP202309'),
('CTP20231103B', 'Bộ', 150, 148, 'PX20231103', 'LOTP202310');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

DROP TABLE IF EXISTS `don_hang`;

CREATE TABLE `don_hang` (
  `IdDonHang` varchar(50) NOT NULL,
  `YeuCau` text DEFAULT NULL,
  `TongTien` float DEFAULT NULL,
  `NgayLap` date DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `IdKhachHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`IdDonHang`, `YeuCau`, `TongTien`, `NgayLap`, `TrangThai`, `IdKhachHang`) VALUES
('DH20231101', 'Lô SV5TOT 87 & 108 phục vụ mùa lễ hội cuối năm', 762600000, '2023-11-10', 'Đang xử lý', 'KH001'),
('DH20231105', 'Bổ sung combo SV5TOT custom cho chương trình Noel', 543000000, '2023-11-15', 'Đang xử lý', 'KH002'),
('DH20231202', 'Đơn chuẩn bị Tết Dương lịch cho TechHub', 687200000, '2023-12-02', 'Đang xử lý', 'KH003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoat_dong_he_thong`
--

DROP TABLE IF EXISTS `hoat_dong_he_thong`;

CREATE TABLE `hoat_dong_he_thong` (
  `IdHoatDong` varchar(50) NOT NULL,
  `HanhDong` text DEFAULT NULL,
  `ThoiGian` datetime DEFAULT NULL,
  `IdNguoiDung` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoat_dong_he_thong`
--

INSERT INTO `hoat_dong_he_thong` (`IdHoatDong`, `HanhDong`, `ThoiGian`, `IdNguoiDung`) VALUES
('HDHT2023110101', 'Tạo kế hoạch lắp ráp SV5TOT 87 KHSX20231101', '2023-11-09 08:15:00', 'ND001'),
('HDHT2023111201', 'Duyệt phiếu xuất switch PX20231102', '2023-11-12 09:45:00', 'ND002'),
('HDHT2023112001', 'Cập nhật tiến độ kiểm thử SV5TOT 108 lô LOTP202310', '2023-11-20 16:20:00', 'ND003');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

DROP TABLE IF EXISTS `hoa_don`;

CREATE TABLE `hoa_don` (
  `IdHoaDon` varchar(50) NOT NULL,
  `NgayLap` date DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `LoaiHD` varchar(255) DEFAULT NULL,
  `IdDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`IdHoaDon`, `NgayLap`, `TrangThai`, `LoaiHD`, `IdDonHang`) VALUES
('HD20231101', '2023-11-20', 'Đã phát hành', 'Hóa đơn GTGT', 'DH20231101'),
('HD20231105', '2023-11-25', 'Đã phát hành', 'Hóa đơn GTGT', 'DH20231105'),
('HD20231202', '2023-12-05', 'Đang đối soát', 'Hóa đơn GTGT', 'DH20231202');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ke_hoach_san_xuat`
--

DROP TABLE IF EXISTS `ke_hoach_san_xuat`;

CREATE TABLE `ke_hoach_san_xuat` (
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `ThoiGianBD` datetime DEFAULT NULL,
  `BANIAMDOC IdNhanVien` varchar(50) NOT NULL,
  `IdTTCTDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ke_hoach_san_xuat`
--

INSERT INTO `ke_hoach_san_xuat` (`IdKeHoachSanXuat`, `SoLuong`, `ThoiGianKetThuc`, `TrangThai`, `ThoiGianBD`, `BANIAMDOC IdNhanVien`, `IdTTCTDonHang`) VALUES
('KHSX20231101', 180, '2023-11-19 17:00:00', 'Đang lắp ráp SV5TOT 87', '2023-11-10 07:30:00', 'NV001', 'CTDH20231101A'),
('KHSX20231102', 120, '2023-11-22 17:30:00', 'Đang kiểm thử SV5TOT 108', '2023-11-14 08:00:00', 'NV001', 'CTDH20231101B'),
('KHSX20231105', 150, '2023-11-26 16:00:00', 'Đang hoàn thiện đơn custom', '2023-11-18 07:30:00', 'NV001', 'CTDH20231105A'),
('KHSX20231202', 160, '2023-12-20 16:30:00', 'Đang chuẩn bị kit TechHub', '2023-12-10 08:00:00', 'NV001', 'CTDH20231202B'),
('KHSX20231202A', 140, '2023-12-22 17:00:00', 'Chuẩn bị SV5TOT 108 TechHub', '2023-12-12 07:45:00', 'NV001', 'CTDH20231202A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ke_hoach_san_xuat_xuong`
--

DROP TABLE IF EXISTS `ke_hoach_san_xuat_xuong`;

CREATE TABLE `ke_hoach_san_xuat_xuong` (
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `TenThanhThanhPhanSP` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `ThoiGianBatDau` datetime DEFAULT NULL,
  `ThoiGianKetThuc` datetime DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `IdXuong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ke_hoach_san_xuat_xuong`
--

INSERT INTO `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`, `TenThanhThanhPhanSP`, `SoLuong`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TrangThai`, `IdKeHoachSanXuat`, `IdXuong`) VALUES
('KHSXX202311A', 'Lắp switch SV5TOT 87', 180, '2023-11-10 08:00:00', '2023-11-12 17:00:00', 'Đang làm', 'KHSX20231101', 'XU001'),
('KHSXX202311B', 'Hoàn thiện & đóng gói SV5TOT 87', 180, '2023-11-13 08:00:00', '2023-11-16 21:30:00', 'Đang làm', 'KHSX20231101', 'XU002'),
('KHSXX202311C', 'Kiểm thử PCB SV5TOT 108', 120, '2023-11-14 08:30:00', '2023-11-18 17:30:00', 'Chuẩn bị', 'KHSX20231102', 'XU001'),
('KHSXX202311D', 'Lắp ráp SV5TOT custom cho Noel', 150, '2023-11-18 08:00:00', '2023-11-23 16:30:00', 'Đang làm', 'KHSX20231105', 'XU001'),
('KHSXX202312A', 'Đóng gói kit custom TechHub', 160, '2023-12-12 08:00:00', '2023-12-18 17:00:00', 'Lập kế hoạch', 'KHSX20231202', 'XU002'),
('KHSXX202312B', 'Lắp ráp SV5TOT 108 TechHub', 140, '2023-12-12 09:00:00', '2023-12-20 18:00:00', 'Chuẩn bị', 'KHSX20231202A', 'XU001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

DROP TABLE IF EXISTS `khach_hang`;

CREATE TABLE `khach_hang` (
  `IdKhachHang` varchar(50) NOT NULL,
  `HoTen` varchar(255) DEFAULT NULL,
  `GioiTinh` tinyint(3) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SoLuongDonHang` int(10) DEFAULT NULL,
  `SoDienThoai` varchar(12) DEFAULT NULL,
  `TongTien` float DEFAULT NULL,
  `LoaiKhachHang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

INSERT INTO `khach_hang` (`IdKhachHang`, `HoTen`, `GioiTinh`, `DiaChi`, `SoLuongDonHang`, `SoDienThoai`, `TongTien`, `LoaiKhachHang`) VALUES
('KH001', 'Công ty GearZone Việt Nam', 1, 'Số 25 Nguyễn Huệ, Quận 1, TP.HCM', 12, '0283899123', 1526000000, 'Đại lý phân phối'),
('KH002', 'Chuỗi cửa hàng TechHub', 1, 'Quốc lộ 13, phường Hiệp Bình Phước, TP.Thủ Đức', 18, '02837261111', 1268000000, 'Bán lẻ chiến lược'),
('KH003', 'Cửa hàng CustomVN', 0, 'Ấp Phú Hòa, xã An Tây, Bến Cát', 9, '0913123456', 732000000, 'Đại lý custom gear');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kho`
--

DROP TABLE IF EXISTS `kho`;

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
('KHO01', 'Kho Linh Kiện Bàn Phím', 'Linh kiện', 'Lô A2, KCN Phúc An, Bến Cát', 3, 2150000000, 'Đang sử dụng', 1400, 'XU001', 'NV004'),
('KHO02', 'Kho Thành Phẩm SV5TOT', 'Thành phẩm', 'Lô B1, KCN Phúc An, Bến Cát', 3, 3480000000, 'Đang sử dụng', 1150, 'XU002', 'NV005');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lo`
--

DROP TABLE IF EXISTS `lo`;

CREATE TABLE `lo` (
  `IdLo` varchar(50) NOT NULL,
  `TenLo` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `NgayTao` datetime DEFAULT NULL,
  `LoaiLo` varchar(255) DEFAULT NULL,
  `IdSanPham` varchar(50) NOT NULL,
  `IdKho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lo`
--

INSERT INTO `lo` (`IdLo`, `TenLo`, `SoLuong`, `NgayTao`, `LoaiLo`, `IdSanPham`, `IdKho`) VALUES
('LOSW202309', 'Lô switch Lotus 09/2023', 1000, '2023-09-01 07:45:00', 'Linh kiện', 'SPCOMP01', 'KHO01'),
('LOPCB202310', 'Lô PCB SV5TOT R3 10/2023', 800, '2023-10-03 08:15:00', 'Linh kiện', 'SPCOMP02', 'KHO01'),
('LOKEY202311', 'Lô keycap PBT Glacier 11/2023', 600, '2023-11-04 09:20:00', 'Linh kiện', 'SPCOMP03', 'KHO01'),
('LOTP202309', 'Lô SV5TOT 87 hoàn thiện 09/2023', 500, '2023-09-10 08:00:00', 'Thành phẩm', 'SPKB87', 'KHO02'),
('LOTP202310', 'Lô SV5TOT 108 hoàn thiện 10/2023', 600, '2023-10-05 09:00:00', 'Thành phẩm', 'SPKB108', 'KHO02'),
('LOTP202311', 'Lô SV5TOT DIY kit 11/2023', 450, '2023-11-02 08:30:00', 'Thành phẩm', 'SPKBCUSTOM', 'KHO02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

DROP TABLE IF EXISTS `nguoi_dung`;

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
('ND001', 'ql.lan', 'matkhau@123', 'Hoạt động', 'NV001', 'VT_QUANLY_XUONG'),
('ND002', 'ketoan.tai', 'matkhau@123', 'Hoạt động', 'NV006', 'VT_KETOAN'),
('ND003', 'admin.minh', 'Matkhau!2023', 'Hoạt động', 'NV002', 'VT_ADMIN'),
('ND004', 'sx.anh', 'matkhau@123', 'Hoạt động', 'NV003', 'VT_NHANVIEN_SANXUAT'),
('ND005', 'kho.trang', 'matkhau@123', 'Hoạt động', 'NV004', 'VT_NHANVIEN_KHO'),
('ND006', 'cl.hanh', 'matkhau@123', 'Hoạt động', 'NV007', 'VT_KIEM_SOAT_CL'),
('ND007', 'kd.long', 'matkhau@123', 'Hoạt động', 'NV008', 'VT_KINH_DOANH'),
('ND008', 'nhansu.mai', 'matkhau@123', 'Hoạt động', 'NV010', 'VT_NHAN_SU'),
('ND009', 'vantai.nam', 'matkhau@123', 'Hoạt động', 'NV009', 'VT_DOI_TAC_VAN_TAI');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguyen_lieu`
--

DROP TABLE IF EXISTS `nguyen_lieu`;

CREATE TABLE `nguyen_lieu` (
  `IdNguyenLieu` varchar(50) NOT NULL,
  `TenNL` varchar(255) DEFAULT NULL,
  `SoLuong` int(10) DEFAULT NULL,
  `DonGian` float DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `NgaySanXuat` datetime DEFAULT NULL,
  `NgayHetHan` datetime DEFAULT NULL,
  `IdLo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguyen_lieu`
--

INSERT INTO `nguyen_lieu` (`IdNguyenLieu`, `TenNL`, `SoLuong`, `DonGian`, `TrangThai`, `NgaySanXuat`, `NgayHetHan`, `IdLo`) VALUES
('NL001', 'Switch Lotus Linear', 400, 450000, 'Đang sử dụng', '2023-09-01 09:00:00', '2025-09-01 00:00:00', 'LOSW202309'),
('NL002', 'PCB SV5TOT R3', 300, 520000, 'Đang sử dụng', '2023-10-03 09:30:00', '2025-10-03 00:00:00', 'LOPCB202310'),
('NL003', 'Keycap PBT Glacier', 280, 690000, 'Đang sử dụng', '2023-11-04 10:15:00', '2025-11-04 00:00:00', 'LOKEY202311');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhan_vien`
--

DROP TABLE IF EXISTS `nhan_vien`;

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
  `idXuong` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`) VALUES
('NV001', 'Nguyễn Thị Lan', '1985-05-10', 0, 'Quản đốc xưởng lắp ráp', 5, 'Đang làm việc', 'Khu phố 3, phường Phú Lợi, TP.Thủ Dầu Một', '2020-01-15 07:30:00', NULL, 'XU001'),
('NV002', 'Trần Văn Minh', '1982-11-22', 1, 'Kỹ sư vận hành dây chuyền SMT', 4, 'Đang làm việc', 'Đường N4, KCN VSIP 1, Bình Dương', '2019-07-01 07:30:00', NULL, NULL),
('NV003', 'Lê Hoàng Anh', '1990-03-18', 1, 'Tổ trưởng lắp ráp switch', 3, 'Đang làm việc', 'Ấp 2, xã Phú An, Bến Cát', '2021-03-10 07:45:00', NULL, 'XU001'),
('NV004', 'Phạm Thu Trang', '1992-08-05', 0, 'Thủ kho linh kiện', 3, 'Đang làm việc', 'Khu phố Đông, phường Hòa Phú, TP.Thủ Dầu Một', '2020-09-01 08:00:00', NULL, NULL),
('NV005', 'Đặng Quốc Việt', '1988-01-26', 1, 'Tổ trưởng hoàn thiện & QA', 3, 'Đang làm việc', 'Phường Chánh Nghĩa, TP.Thủ Dầu Một', '2021-06-21 07:20:00', NULL, NULL),
('NV006', 'Vũ Hữu Tài', '1983-09-14', 1, 'Kế toán trưởng', 4, 'Đang làm việc', 'Khu phố 5, thị trấn Mỹ Phước, Bến Cát', '2018-04-02 08:05:00', NULL, NULL),
('NV007', 'Đào Ngọc Hạnh', '1987-04-19', 0, 'Trưởng nhóm QA bàn phím', 4, 'Đang làm việc', 'Phường Hiệp Thành, TP.Thủ Dầu Một', '2019-02-11 08:00:00', NULL, NULL),
('NV008', 'Phạm Đức Long', '1989-12-02', 1, 'Chuyên viên kinh doanh thiết bị', 3, 'Đang làm việc', 'Phường Hưng Định, TP.Thuận An', '2020-05-05 08:15:00', NULL, NULL),
('NV009', 'Nguyễn Hải Nam', '1991-07-23', 1, 'Điều phối logistics', 3, 'Đang làm việc', 'Phường Mỹ Phước, TX.Bến Cát', '2021-08-09 07:50:00', NULL, NULL),
('NV010', 'Võ Thị Mai', '1986-03-30', 0, 'Chuyên viên nhân sự sản xuất', 4, 'Đang làm việc', 'Phường Phú Tân, TP.Thủ Dầu Một', '2018-09-17 08:10:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu`
--

DROP TABLE IF EXISTS `phieu`;

CREATE TABLE `phieu` (
  `IdPhieu` varchar(50) NOT NULL,
  `NgayLP` date DEFAULT NULL,
  `NgayXN` date DEFAULT NULL,
  `TongTien` int(10) DEFAULT NULL,
  `LoaiPhieu` varchar(255) DEFAULT NULL,
  `IdKho` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien2` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu`
--

INSERT INTO `phieu` (`IdPhieu`, `NgayLP`, `NgayXN`, `TongTien`, `LoaiPhieu`, `IdKho`, `NHAN_VIENIdNhanVien`, `NHAN_VIENIdNhanVien2`) VALUES
('PN20231101', '2023-11-05', '2023-11-05', 284000000, 'Phiếu nhập linh kiện switch & PCB', 'KHO01', 'NV004', 'NV006'),
('PX20231102', '2023-11-12', '2023-11-12', 203850000, 'Phiếu xuất linh kiện cho lắp ráp', 'KHO01', 'NV004', 'NV001'),
('PX20231103', '2023-11-18', '2023-11-18', 916500000, 'Phiếu xuất thành phẩm SV5TOT', 'KHO02', 'NV005', 'NV002');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

DROP TABLE IF EXISTS `san_pham`;

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
('SPKB87', 'SV5TOT 87 RGB', 'Bộ', 2450000, 'Bàn phím cơ TKL với foam tiêu âm và switch Lotus'),
('SPKB108', 'SV5TOT 108 Silent', 'Bộ', 2680000, 'Bàn phím cơ full-size switch Silent bôi trơn sẵn'),
('SPKBCUSTOM', 'SV5TOT DIY Kit', 'Bộ', 1950000, 'Bộ kit custom layout 75% với PCB hotswap'),
('SPCOMP01', 'Switch Lotus Linear', 'Hộp', 450000, 'Hộp 90 switch linear Lotus được bôi trơn sẵn'),
('SPCOMP02', 'PCB SV5TOT R3', 'Tấm', 520000, 'PCB hotswap hỗ trợ layout 87/88 phím'),
('SPCOMP03', 'Keycap PBT Glacier', 'Bộ', 690000, 'Keycap PBT double-shot profile Cherry màu Glacier');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_pham`
--

DROP TABLE IF EXISTS `thanh_pham`;

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
('TP20231101', 'SV5TOT 87 bản RGB', 'Lắp ráp hoàn thiện kèm foam tiêu âm', 2550000, 'Loại A', 'LOTP202309'),
('TP20231102', 'SV5TOT 108 bản Silent', 'Dán tem theo nhận diện GearZone', 2780000, 'Loại A', 'LOTP202310'),
('TP20231103', 'SV5TOT DIY kit tùy chỉnh', 'Đóng gói đủ phụ kiện custom theo yêu cầu', 2050000, 'Loại B', 'LOTP202311');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--

DROP TABLE IF EXISTS `ttct_bien_ban_danh_gia_dot_xuat`;

CREATE TABLE `ttct_bien_ban_danh_gia_dot_xuat` (
  `IdTTCTBBDGDX` varchar(50) NOT NULL,
  `LoaiTieuChi` varchar(255) DEFAULT NULL,
  `TieuChi` varchar(255) DEFAULT NULL,
  `DiemDG` int(10) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `IdBienBanDanhGiaDX` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--

INSERT INTO `ttct_bien_ban_danh_gia_dot_xuat` (`IdTTCTBBDGDX`, `LoaiTieuChi`, `TieuChi`, `DiemDG`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaDX`) VALUES
('CTBBDX20231101A', 'An toàn điện', 'Kiểm tra tiếp địa workstation', 92, 'Đảm bảo điện trở nối đất < 1Ω', 'kiem_tra_tiep_dia.jpg', 'BBDX20231101'),
('CTBBDX20231101B', 'Vệ sinh công nghiệp', 'Dọn sạch bàn lắp ráp', 90, 'Cần bổ sung checklist vệ sinh cuối ca', 've_sinh_workbench.jpg', 'BBDX20231101'),
('CTBBDX20231102A', 'Kiểm soát linh kiện', 'Quản lý số serial PCB', 91, 'Serial được cập nhật đầy đủ trong MES', 'quan_ly_serial.jpg', 'BBDX20231102'),
('CTBBDX20231102B', 'Hồ sơ truy xuất', 'Nhật ký thay switch lỗi', 89, 'Đề nghị bổ sung ảnh minh chứng switch lỗi', 'nhat_ky_switch.jpg', 'BBDX20231102');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--

DROP TABLE IF EXISTS `ttct_bien_ban_danh_gia_thanh_pham`;

CREATE TABLE `ttct_bien_ban_danh_gia_thanh_pham` (
  `IdTTCTBBDGTP` varchar(50) NOT NULL,
  `Tieuchi` varchar(255) DEFAULT NULL,
  `DiemD` int(10) DEFAULT NULL,
  `GhiChu` varchar(255) DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `IdBienBanDanhGiaSP` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--

INSERT INTO `ttct_bien_ban_danh_gia_thanh_pham` (`IdTTCTBBDGTP`, `Tieuchi`, `DiemD`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaSP`) VALUES
('CTBBTP20231101A', 'Độ đồng đều lực nhấn phím', 95, 'Sai số lực nhấn ±5g', 'force_test.jpg', 'BBTP20231101'),
('CTBBTP20231101B', 'Chất lượng LED RGB', 94, 'LED sáng đều, không chết điểm', 'led_rgb.jpg', 'BBTP20231101'),
('CTBBTP20231102A', 'Độ ồn switch Silent', 93, 'Đạt 42dB tại 10cm', 'do_on_silent.jpg', 'BBTP20231102'),
('CTBBTP20231102B', 'Sai số layout', 92, 'Khoảng cách phím đúng chuẩn ANSI', 'layout_ansi.jpg', 'BBTP20231102'),
('CTBBTP20231103A', 'Độ hoàn thiện bề mặt', 91, 'Anod nhôm đều màu, không xước', 'be_mat_vo.jpg', 'BBTP20231103'),
('CTBBTP20231103B', 'Đóng gói phụ kiện', 90, 'Đủ keycap, cable, tool tháo key', 'phu_kien_day_du.jpg', 'BBTP20231103');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

DROP TABLE IF EXISTS `vai_tro`;

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
('VT_DOI_TAC_VAN_TAI', 'Đối tác vận tải'),
('VT_KETOAN', 'Kế toán'),
('VT_KHACH', 'Khách hàng nội bộ'),
('VT_KIEM_SOAT_CL', 'Kiểm soát chất lượng'),
('VT_KINH_DOANH', 'Kinh doanh'),
('VT_NHANVIEN_KHO', 'Nhân viên kho'),
('VT_NHANVIEN_SANXUAT', 'Nhân viên sản xuất'),
('VT_NHAN_SU', 'Nhân sự'),
('VT_QUANLY_XUONG', 'Quản lý xưởng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xuong`
--

DROP TABLE IF EXISTS `xuong`;

CREATE TABLE `xuong` (
  `IdXuong` varchar(50) NOT NULL,
  `TenXuong` varchar(255) DEFAULT NULL,
  `SlThietBi` int(10) DEFAULT NULL,
  `SlNhanVien` int(10) DEFAULT NULL,
  `TenQuyTrinh` varchar(255) DEFAULT NULL,
  `TrangThai` varchar(255) NOT NULL,
  `XUONGTRUONG_IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `xuong`
--

INSERT INTO `xuong` (`IdXuong`, `TenXuong`, `SlThietBi`, `SlNhanVien`, `TenQuyTrinh`, `TrangThai`, `XUONGTRUONG_IdNhanVien`) VALUES
('XU001', 'Xưởng Lắp Ráp SV5TOT', 25, 40, 'Lắp ráp & hiệu chỉnh bàn phím', 'Đang hoạt động', 'NV001'),
('XU002', 'Xưởng Kiểm Định & Đóng Gói', 18, 28, 'Kiểm thử & đóng gói thành phẩm', 'Đang hoạt động', 'NV002');

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
  ADD KEY `FKKE_HOACH_S691979` (`BANIAMDOC IdNhanVien`);

--
-- Chỉ mục cho bảng `ke_hoach_san_xuat_xuong`
--
ALTER TABLE `ke_hoach_san_xuat_xuong`
  ADD PRIMARY KEY (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKKE_HOACH_S948077` (`IdXuong`),
  ADD KEY `FKKE_HOACH_S948390` (`IdKeHoachSanXuat`);

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
  ADD KEY `idXuong` (`idXuong`);

--
-- Chỉ mục cho bảng `phieu`
--
ALTER TABLE `phieu`
  ADD PRIMARY KEY (`IdPhieu`),
  ADD KEY `FKPHIEU116698` (`IdKho`),
  ADD KEY `Nhan vien lap phieu` (`NHAN_VIENIdNhanVien`),
  ADD KEY `Nhan vien xac nhan phieu` (`NHAN_VIENIdNhanVien2`);

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
  ADD CONSTRAINT `FKCT_DON_HAN864902` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`);

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
  ADD CONSTRAINT `FKKE_HOACH_S691979` FOREIGN KEY (`BANIAMDOC IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

--
-- Các ràng buộc cho bảng `ke_hoach_san_xuat_xuong`
--
ALTER TABLE `ke_hoach_san_xuat_xuong`
  ADD CONSTRAINT `FKKE_HOACH_S948077` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `FKKE_HOACH_S948390` FOREIGN KEY (`IdKeHoachSanXuat`) REFERENCES `ke_hoach_san_xuat` (`IdKeHoachSanXuat`);

--
-- Các ràng buộc cho bảng `kho`
--
ALTER TABLE `kho`
  ADD CONSTRAINT `FKKHO901694` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `Nhan vien kho` FOREIGN KEY (`NHAN_VIEN_KHO_IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`);

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
  ADD CONSTRAINT `nhan_vien_ibfk_1` FOREIGN KEY (`idXuong`) REFERENCES `xuong` (`IdXuong`);

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

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
