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

CREATE TABLE `bang_luong` (
  `IdBangLuong` varchar(50) NOT NULL,
  `KETOAN IdNhanVien2` varchar(50) NOT NULL,
  `NHAN_VIENIdNhanVien` varchar(50) NOT NULL,
  `ThangNam` int(11) DEFAULT NULL,
  `LuongCoBan` float DEFAULT NULL,
  `PhuCap` int(10) DEFAULT NULL,
  `KhauTru` float DEFAULT NULL,
  `ThueTNCN` int(10) DEFAULT NULL,
  `TongThuNhap` int(10) DEFAULT NULL,
  `TrangThai` varchar(255) DEFAULT NULL,
  `NgayLap` date DEFAULT NULL,
  `ChuKy` varbinary(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bang_luong`
--

INSERT INTO `bang_luong` (`IdBangLuong`, `KETOAN IdNhanVien2`, `NHAN_VIENIdNhanVien`, `ThangNam`, `LuongCoBan`, `PhuCap`, `KhauTru`, `ThueTNCN`, `TongThuNhap`, `TrangThai`, `NgayLap`, `ChuKy`) VALUES
('BL202311NV003', 'NV006', 'NV003', 202311, 8500000, 1200000, 500000, 700000, 9200000, 'Chờ duyệt', '2023-11-28', 0x61646d696e2e6d696e68),
('BL202311NV004', 'NV006', 'NV004', 202311, 9000000, 1000000, 600000, 750000, 9350000, 'Chờ duyệt', '2023-11-28', 0x61646d696e2e6d696e68),
('BL202311NV005', 'NV006', 'NV005', 202311, 9500000, 1300000, 650000, 780000, 10180000, 'Chờ duyệt', '2023-11-28', 0x61646d696e2e6d696e68);

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

--
-- Đang đổ dữ liệu cho bảng `bien_ban_danh_gia_dot_xuat`
--

INSERT INTO `bien_ban_danh_gia_dot_xuat` (`IdBienBanDanhGiaDX`, `ThoiGian`, `TongTCD`, `TongTCKD`, `KetQua`, `IdXuong`, `IdNhanVien`) VALUES
('BBDX20231101', '2023-11-14 10:00:00', 92, 8, 'Đạt chuẩn', 'XU001', 'NV001'),
('BBDX20231102', '2023-11-20 09:00:00', 90, 10, 'Đạt chuẩn', 'XU002', 'NV002');

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
('CA202311S1', 'Ca sáng phối trộn bánh bơ', 'Sản xuất', '2023-11-11', '2023-11-11 07:30:00', '2023-11-11 15:30:00', 35, 'KHSXX202311A', 'LONL202309'),
('CA202311S2', 'Ca tối đóng gói bánh bơ', 'Đóng gói', '2023-11-13', '2023-11-13 14:00:00', '2023-11-13 22:00:00', 28, 'KHSXX202311B', 'LOTP202309'),
('CA202311S3', 'Ca đêm đóng gói tăng ca', 'Đóng gói', '2023-11-16', '2023-11-16 21:00:00', '2023-11-17 05:00:00', 24, 'KHSXX202311B', 'LOTP202309'),
('CA202312S1', 'Ca sáng chuẩn bị cracker', 'Chuẩn bị', '2023-12-12', '2023-12-12 07:30:00', '2023-12-12 15:30:00', 30, 'KHSXX202312A', 'LONL202310');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cham_cong`
--

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
('CTKHSXX202311A', 320, 'KHSXX202311A', 'NL001'),
('CTKHSXX202311B', 120, 'KHSXX202311A', 'NL002'),
('CTKHSXX202311C', 90, 'KHSXX202311A', 'NL003'),
('CTKHSXX202311D', 140, 'KHSXX202311C', 'NL002'),
('CTKHSXX202311E', 80, 'KHSXX202311D', 'NL001'),
('CTKHSXX202312A', 110, 'KHSXX202312A', 'NL002'),
('CTKHSXX202312B', 70, 'KHSXX202312B', 'NL003');

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
  `IdDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ct_don_hang`
--

INSERT INTO `ct_don_hang` (`IdTTCTDonHang`, `SoLuong`, `NgayGiao`, `YeuCau`, `DonGia`, `ThanhTien`, `GhiChu`, `VAT`, `IdSanPham`, `IdDonHang`) VALUES
('CTDH20231101A', 250, '2023-11-25 09:00:00', 'Đóng gói thùng carton in logo khách hàng', 450000, 112500000, 'Giao bằng xe lạnh', 0.08, 'SPBANH01', 'DH20231101'),
('CTDH20231101B', 150, '2023-11-26 13:30:00', 'Kiểm soát chất lượng 100% trước khi xuất kho', 480000, 72000000, 'Ưu tiên date mới', 0.08, 'SPBANH02', 'DH20231101'),
('CTDH20231105A', 180, '2023-11-28 08:00:00', 'Thêm tem khuyến mãi cuối năm', 450000, 81000000, 'Đóng gói theo pallet', 0.08, 'SPBANH01', 'DH20231105'),
('CTDH20231105B', 120, '2023-11-29 14:00:00', 'Bọc co nhiệt từng thùng', 420000, 50400000, 'Ghi chú nhiệt độ bảo quản 20°C', 0.08, 'SPBANH03', 'DH20231105'),
('CTDH20231202A', 140, '2023-12-20 10:00:00', 'In kèm thông điệp mừng năm mới', 480000, 67200000, 'Lịch giao buổi sáng', 0.08, 'SPBANH02', 'DH20231202'),
('CTDH20231202B', 160, '2023-12-22 15:00:00', 'Gia cố bìa carton hai lớp', 420000, 67200000, 'Yêu cầu xe nâng khi bốc hàng', 0.08, 'SPBANH03', 'DH20231202');

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

--
-- Đang đổ dữ liệu cho bảng `ct_hoa_don`
--

INSERT INTO `ct_hoa_don` (`IdCTHoaDon`, `SoLuong`, `ThueVAT`, `TongTien`, `PhuongThucTT`, `IdHoaDon`, `IdLo`) VALUES
('CTHD20231101A', 250, 8, 121500000, 'Chuyển khoản', 'HD20231101', 'LOTP202309'),
('CTHD20231101B', 150, 8, 77760000, 'Chuyển khoản', 'HD20231101', 'LOTP202310'),
('CTHD20231105A', 180, 8, 87480000, 'Chuyển khoản', 'HD20231105', 'LOTP202309'),
('CTHD20231105B', 120, 8, 54432000, 'Chuyển khoản', 'HD20231105', 'LOTP202311'),
('CTHD20231202A', 140, 8, 72576000, 'Chuyển khoản', 'HD20231202', 'LOTP202310'),
('CTHD20231202B', 160, 8, 72576000, 'Chuyển khoản', 'HD20231202', 'LOTP202311');

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

--
-- Đang đổ dữ liệu cho bảng `ct_phieu`
--

INSERT INTO `ct_phieu` (`IdTTCTPhieu`, `DonViTinh`, `SoLuong`, `ThucNhan`, `IdPhieu`, `IdLo`) VALUES
('CTP20231101A', 'Bao', 200, 200, 'PN20231101', 'LONL202309'),
('CTP20231101B', 'Bao', 150, 150, 'PN20231101', 'LONL202310'),
('CTP20231102A', 'Kg', 500, 500, 'PX20231102', 'LONL202309'),
('CTP20231102B', 'Kg', 350, 340, 'PX20231102', 'LONL202310'),
('CTP20231103A', 'Thùng', 220, 220, 'PX20231103', 'LOTP202309'),
('CTP20231103B', 'Thùng', 150, 148, 'PX20231103', 'LOTP202310');

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
  `IdKhachHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`IdDonHang`, `YeuCau`, `TongTien`, `NgayLap`, `TrangThai`, `IdKhachHang`) VALUES
('DH20231101', 'Giao hàng đợt 1 phục vụ mùa lễ hội cuối năm', 184500000, '2023-11-10', 'Đang xử lý', 'KH001'),
('DH20231105', 'Bổ sung hàng khuyến mãi Noel cho hệ thống siêu thị', 131400000, '2023-11-15', 'Đang xử lý', 'KH002'),
('DH20231202', 'Đơn chuẩn bị Tết Dương lịch', 134400000, '2023-12-02', 'Đang xử lý', 'KH003');

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

--
-- Đang đổ dữ liệu cho bảng `hoat_dong_he_thong`
--

INSERT INTO `hoat_dong_he_thong` (`IdHoatDong`, `HanhDong`, `ThoiGian`, `IdNguoiDung`) VALUES
('HDHT2023110101', 'Tạo kế hoạch sản xuất KHSX20231101', '2023-11-09 08:15:00', 'ND001'),
('HDHT2023111201', 'Duyệt phiếu xuất PX20231102', '2023-11-12 09:45:00', 'ND002'),
('HDHT2023112001', 'Cập nhật tiến độ đóng gói lô LOTP202309', '2023-11-20 16:20:00', 'ND003');

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
('KHSX20231101', 260, '2023-11-19 17:00:00', 'Đang triển khai', '2023-11-10 07:30:00', 'NV001', 'CTDH20231101A'),
('KHSX20231102', 170, '2023-11-22 17:30:00', 'Đang chuẩn bị', '2023-11-14 08:00:00', 'NV001', 'CTDH20231101B'),
('KHSX20231105', 195, '2023-11-26 16:00:00', 'Đang triển khai', '2023-11-18 07:30:00', 'NV001', 'CTDH20231105A'),
('KHSX20231202', 170, '2023-12-20 16:30:00', 'Đang lập kế hoạch', '2023-12-10 08:00:00', 'NV001', 'CTDH20231202B'),
('KHSX20231202A', 150, '2023-12-22 17:00:00', 'Chuẩn bị nguyên liệu', '2023-12-12 07:45:00', 'NV001', 'CTDH20231202A');

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
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `IdXuong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ke_hoach_san_xuat_xuong`
--

INSERT INTO `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`, `TenThanhThanhPhanSP`, `SoLuong`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TrangThai`, `IdKeHoachSanXuat`, `IdXuong`) VALUES
('KHSXX202311A', 'Nhào bột và tạo hình bánh bơ', 260, '2023-11-10 08:00:00', '2023-11-12 17:00:00', 'Đang làm', 'KHSX20231101', 'XU001'),
('KHSXX202311B', 'Đóng gói thành phẩm bánh bơ', 260, '2023-11-13 08:00:00', '2023-11-16 21:30:00', 'Đang làm', 'KHSX20231101', 'XU002'),
('KHSXX202311C', 'Phối trộn socola chip', 170, '2023-11-14 08:30:00', '2023-11-18 17:30:00', 'Chuẩn bị', 'KHSX20231102', 'XU001'),
('KHSXX202311D', 'Chuẩn bị bánh bơ khuyến mãi', 195, '2023-11-18 08:00:00', '2023-11-23 16:30:00', 'Đang làm', 'KHSX20231105', 'XU001'),
('KHSXX202312A', 'Đóng gói cracker mè đen', 170, '2023-12-12 08:00:00', '2023-12-18 17:00:00', 'Lập kế hoạch', 'KHSX20231202', 'XU002'),
('KHSXX202312B', 'Phối trộn socola dịp Tết', 150, '2023-12-12 09:00:00', '2023-12-20 18:00:00', 'Chuẩn bị', 'KHSX20231202A', 'XU001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khach_hang`
--

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
('KH001', 'Công ty Thực phẩm Ánh Dương', 1, 'Số 25 Nguyễn Huệ, Quận 1, TP.HCM', 12, '0283899123', 525000000, 'Bán buôn chiến lược'),
('KH002', 'Siêu thị Bình Minh', 1, 'Quốc lộ 13, phường Hiệp Bình Phước, TP.Thủ Đức', 18, '02837261111', 468000000, 'Đối tác siêu thị'),
('KH003', 'Cửa hàng Tạp hóa Cô Ba', 0, 'Ấp Phú Hòa, xã An Tây, Bến Cát', 9, '0913123456', 189000000, 'Đại lý khu vực');

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
('KHO01', 'Kho Nguyên Liệu', 'Nguyên liệu', 'Lô A2, KCN Phúc An, Bến Cát', 3, 125600000, 'Đang sử dụng', 1500, 'XU001', 'NV004'),
('KHO02', 'Kho Thành Phẩm', 'Thành phẩm', 'Lô B1, KCN Phúc An, Bến Cát', 3, 235800000, 'Đang sử dụng', 1550, 'XU002', 'NV005');

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
  `IdSanPham` varchar(50) NOT NULL,
  `IdKho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lo`
--

INSERT INTO `lo` (`IdLo`, `TenLo`, `SoLuong`, `NgayTao`, `LoaiLo`, `IdSanPham`, `IdKho`) VALUES
('LONL202309', 'Lô bột mì số 8 - 09/2023', 1000, '2023-09-01 07:45:00', 'Nguyên liệu', 'SPNL01', 'KHO01'),
('LONL202310', 'Lô đường tinh luyện - 10/2023', 800, '2023-10-03 08:15:00', 'Nguyên liệu', 'SPNL02', 'KHO01'),
('LONL202311', 'Lô bơ lạt - 11/2023', 600, '2023-11-04 09:20:00', 'Nguyên liệu', 'SPNL03', 'KHO01'),
('LOTP202309', 'Lô bánh quy bơ tháng 9', 500, '2023-09-10 08:00:00', 'Thành phẩm', 'SPBANH01', 'KHO02'),
('LOTP202310', 'Lô bánh quy socola tháng 10', 600, '2023-10-05 09:00:00', 'Thành phẩm', 'SPBANH02', 'KHO02'),
('LOTP202311', 'Lô bánh cracker mè đen tháng 11', 450, '2023-11-02 08:30:00', 'Thành phẩm', 'SPBANH03', 'KHO02');

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
('NL001', 'Bột mì số 8', 400, 125000, 'Đang sử dụng', '2023-09-01 09:00:00', '2024-03-01 00:00:00', 'LONL202309'),
('NL002', 'Đường cát trắng', 300, 138000, 'Đang sử dụng', '2023-10-03 09:30:00', '2024-04-03 00:00:00', 'LONL202310'),
('NL003', 'Bơ lạt nguyên chất', 250, 900000, 'Đang sử dụng', '2023-11-04 10:15:00', '2024-05-04 00:00:00', 'LONL202311');

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
  `idXuong` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhan_vien`
--

INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`) VALUES
('NV001', 'Nguyễn Thị Lan', '1985-05-10', 0, 'Quản đốc xưởng', 5, 'Đang làm việc', 'Khu phố 3, phường Phú Lợi, TP.Thủ Dầu Một', '2020-01-15 07:30:00', NULL, 'XU001'),
('NV002', 'Trần Văn Minh', '1982-11-22', 1, 'Kỹ sư vận hành', 4, 'Đang làm việc', 'Đường N4, KCN VSIP 1, Bình Dương', '2019-07-01 07:30:00', NULL, NULL),
('NV003', 'Lê Hoàng Anh', '1990-03-18', 1, 'Tổ trưởng chuyền may', 3, 'Đang làm việc', 'Ấp 2, xã Phú An, Bến Cát', '2021-03-10 07:45:00', NULL, 'XU001'),
('NV004', 'Phạm Thu Trang', '1992-08-05', 0, 'Thủ kho nguyên liệu', 3, 'Đang làm việc', 'Khu phố Đông, phường Hòa Phú, TP.Thủ Dầu Một', '2020-09-01 08:00:00', NULL, NULL),
('NV005', 'Đặng Quốc Việt', '1988-01-26', 1, 'Tổ trưởng đóng gói', 3, 'Đang làm việc', 'Phường Chánh Nghĩa, TP.Thủ Dầu Một', '2021-06-21 07:20:00', NULL, NULL),
('NV006', 'Vũ Hữu Tài', '1983-09-14', 1, 'Kế toán trưởng', 4, 'Đang làm việc', 'Khu phố 5, thị trấn Mỹ Phước, Bến Cát', '2018-04-02 08:05:00', NULL, NULL),
('NV007', 'Đào Ngọc Hạnh', '1987-04-19', 0, 'Trưởng nhóm kiểm soát chất lượng', 4, 'Đang làm việc', 'Phường Hiệp Thành, TP.Thủ Dầu Một', '2019-02-11 08:00:00', NULL, NULL),
('NV008', 'Phạm Đức Long', '1989-12-02', 1, 'Chuyên viên kinh doanh', 3, 'Đang làm việc', 'Phường Hưng Định, TP.Thuận An', '2020-05-05 08:15:00', NULL, NULL),
('NV009', 'Nguyễn Hải Nam', '1991-07-23', 1, 'Điều phối kho vận', 3, 'Đang làm việc', 'Phường Mỹ Phước, TX.Bến Cát', '2021-08-09 07:50:00', NULL, NULL),
('NV010', 'Võ Thị Mai', '1986-03-30', 0, 'Chuyên viên nhân sự', 4, 'Đang làm việc', 'Phường Phú Tân, TP.Thủ Dầu Một', '2018-09-17 08:10:00', NULL, NULL);

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
  `NHAN_VIENIdNhanVien2` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phieu`
--

INSERT INTO `phieu` (`IdPhieu`, `NgayLP`, `NgayXN`, `TongTien`, `LoaiPhieu`, `IdKho`, `NHAN_VIENIdNhanVien`, `NHAN_VIENIdNhanVien2`) VALUES
('PN20231101', '2023-11-05', '2023-11-05', 35000000, 'Phiếu nhập nguyên liệu', 'KHO01', 'NV004', 'NV006'),
('PX20231102', '2023-11-12', '2023-11-12', 42000000, 'Phiếu xuất nguyên liệu', 'KHO01', 'NV004', 'NV001'),
('PX20231103', '2023-11-18', '2023-11-18', 68000000, 'Phiếu xuất thành phẩm', 'KHO02', 'NV005', 'NV002');

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
('SPBANH01', 'Bánh quy bơ sữa', 'Thùng', 450000, 'Bánh quy bơ thơm béo, đóng gói 24 gói nhỏ'),
('SPBANH02', 'Bánh quy socola chip', 'Thùng', 480000, 'Bánh quy socola chip giòn tan, hộp quà tặng'),
('SPBANH03', 'Bánh cracker mè đen', 'Thùng', 420000, 'Bánh cracker mè đen dinh dưỡng, gói 350g'),
('SPNL01', 'Bột mì số 8', 'Bao', 120000, 'Nguyên liệu chính cho khâu nhào bột'),
('SPNL02', 'Đường cát trắng tinh luyện', 'Bao', 135000, 'Đường tinh luyện dành cho thực phẩm cao cấp'),
('SPNL03', 'Bơ lạt New Zealand', 'Thùng', 890000, 'Bơ lạt nguyên chất dùng cho sản xuất bánh');

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
('TP20231101', 'Bánh quy bơ thùng 24 gói', 'Đảm bảo đóng gói kín, tem chống hàng giả', 470000, 'Loại A', 'LOTP202309'),
('TP20231102', 'Bánh quy socola hộp quà', 'Bao bì in theo yêu cầu Noel', 520000, 'Loại A+', 'LOTP202310'),
('TP20231103', 'Bánh cracker mè đen bịch lớn', 'Phân phối thử nghiệm miền Tây', 390000, 'Loại B', 'LOTP202311');

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

--
-- Đang đổ dữ liệu cho bảng `ttct_bien_ban_danh_gia_dot_xuat`
--

INSERT INTO `ttct_bien_ban_danh_gia_dot_xuat` (`IdTTCTBBDGDX`, `LoaiTieuChi`, `TieuChi`, `DiemDG`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaDX`) VALUES
('CTBBDX20231101A', 'An toàn lao động', 'Trang bị bảo hộ lao động', 92, 'Đủ trang bị bảo hộ theo quy định', 'bao_ho_lao_dong.jpg', 'BBDX20231101'),
('CTBBDX20231101B', 'Vệ sinh công nghiệp', 'Vệ sinh dây chuyền sản xuất', 90, 'Cần bổ sung vệ sinh khu vực trộn bột cuối ca', 've_sinh_day_chuyen.jpg', 'BBDX20231101'),
('CTBBDX20231102A', 'Kiểm soát đóng gói', 'Niêm phong thùng thành phẩm', 91, 'Niêm phong chắc chắn, tem còn nguyên', 'dong_goi_chat_luong.jpg', 'BBDX20231102'),
('CTBBDX20231102B', 'Hồ sơ truy xuất', 'Cập nhật nhật ký sản xuất', 89, 'Đề nghị cập nhật thêm nhật ký giao ca', 'nhat_ky_san_xuat.jpg', 'BBDX20231102');

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

--
-- Đang đổ dữ liệu cho bảng `ttct_bien_ban_danh_gia_thanh_pham`
--

INSERT INTO `ttct_bien_ban_danh_gia_thanh_pham` (`IdTTCTBBDGTP`, `Tieuchi`, `DiemD`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaSP`) VALUES
('CTBBTP20231101A', 'Độ giòn của bánh', 95, 'Giòn đều, không cháy xém', 'do_gion_banh.jpg', 'BBTP20231101'),
('CTBBTP20231101B', 'Bao bì & nhãn mác', 94, 'Tem in sắc nét, không trầy xước', 'bao_bi_banhbo.jpg', 'BBTP20231101'),
('CTBBTP20231102A', 'Hàm lượng socola', 93, 'Đạt chuẩn 12% socola chip', 'socola_chip.jpg', 'BBTP20231102'),
('CTBBTP20231102B', 'Kích thước đóng gói', 92, 'Đóng gói đúng quy cách 450g/thùng', 'kich_thuoc_thung.jpg', 'BBTP20231102'),
('CTBBTP20231103A', 'Hương vị mè đen', 91, 'Mè rang thơm nhưng cần tăng độ béo', 'huong_vi_me.jpg', 'BBTP20231103'),
('CTBBTP20231103B', 'Độ đồng đều sản phẩm', 90, 'Chênh lệch trọng lượng ±2g', 'dong_deu_sp.jpg', 'BBTP20231103');

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
('XU001', 'Xưởng May Thành Phẩm', 25, 40, 'May & hoàn thiện', 'Đang hoạt động', 'NV001'),
('XU002', 'Xưởng Đóng Gói & Kiểm Định', 18, 28, 'Đóng gói & kiểm soát chất lượng', 'Đang hoạt động', 'NV002');

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
--
-- Cơ sở dữ liệu: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Đang đổ dữ liệu cho bảng `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2019-10-21 13:37:09', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Chỉ mục cho bảng `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Chỉ mục cho bảng `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Chỉ mục cho bảng `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Chỉ mục cho bảng `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Chỉ mục cho bảng `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Chỉ mục cho bảng `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Chỉ mục cho bảng `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Chỉ mục cho bảng `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Chỉ mục cho bảng `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Chỉ mục cho bảng `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Chỉ mục cho bảng `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Chỉ mục cho bảng `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Chỉ mục cho bảng `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Chỉ mục cho bảng `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Chỉ mục cho bảng `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Chỉ mục cho bảng `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Chỉ mục cho bảng `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Cơ sở dữ liệu: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
