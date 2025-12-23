-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 18, 2025 lúc 11:29 AM
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

--
-- Đang đổ dữ liệu cho bảng `bang_luong`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `cau_hinh_nguyen_lieu`
--

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `cau_hinh_san_pham`
--

-- (Dữ liệu đã được lược bỏ)

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hinh_thong_bao`
--

CREATE TABLE `cau_hinh_thong_bao` (
  `MaCauHinh` varchar(100) NOT NULL,
  `GiaTri` text DEFAULT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_hinh_thong_bao`
--

-- (Dữ liệu đã được lược bỏ)

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
  `LOIdLo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ca_lam`
--

-- (Dữ liệu ca làm sẽ được sinh tự động theo kế hoạch xưởng)

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

-- (Không seed dữ liệu chấm công)

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

-- (Không seed dữ liệu chi tiết nguyên liệu kế hoạch xưởng)

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

--
-- Đang đổ dữ liệu cho bảng `ct_don_hang`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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
  `EmailLienHe` varchar(255) DEFAULT NULL,
  `IdKhachHang` varchar(50) NOT NULL,
  `IdNguoiTao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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
  `IdTTCTDonHang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ke_hoach_san_xuat`
--

-- (Dữ liệu đã được lược bỏ)

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
  `TinhTrangVatTu` varchar(255) DEFAULT 'Chưa kiểm tra',
  `IdCongDoan` varchar(50) DEFAULT NULL,
  `IdKeHoachSanXuat` varchar(50) NOT NULL,
  `IdXuong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ke_hoach_san_xuat_xuong`
--

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `khach_hang`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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
-- Cấu trúc bảng cho bảng `phan_cong_ke_hoach_xuong`
--

CREATE TABLE `phan_cong_ke_hoach_xuong` (
  `IdPhanCong` varchar(50) NOT NULL,
  `IdKeHoachSanXuatXuong` varchar(50) NOT NULL,
  `IdNhanVien` varchar(50) NOT NULL,
  `IdCaLamViec` varchar(50) NOT NULL,
  `VaiTro` varchar(255) DEFAULT NULL,
  `NgayPhanCong` datetime DEFAULT NULL
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
  `IdSanPham` varchar(50) NOT NULL,
  `IdKho` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lo`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `phieu`
--

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `product_components`
--

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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

-- (Dữ liệu đã được lược bỏ)

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
('VT_QUANLY_XUONG', 'Xưởng trưởng'),
('VT_KETOAN', 'Kế toán'),
('VT_KHO_TRUONG', 'Kho trưởng'),
('VT_KIEM_SOAT_CL', 'Nhân viên kiểm soát chất lượng'),
('VT_KINH_DOANH', 'Nhân viên kinh doanh'),
('VT_NHANVIEN_KHO', 'Nhân viên kho'),
('VT_NHANVIEN_SANXUAT', 'Nhân viên sản xuất');

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
  `CongSuatDangSuDung` float DEFAULT 0,
  `LoaiXuong` varchar(255) DEFAULT 'Sản xuất',
  `TrangThai` varchar(255) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `XUONGTRUONG_IdNhanVien` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `xuong`
--

-- (Dữ liệu đã được lược bỏ)

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

--
-- Đang đổ dữ liệu cho bảng `xuong_cau_hinh_san_pham`
--

-- (Dữ liệu đã được lược bỏ)

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
-- Chỉ mục cho bảng `phan_cong_ke_hoach_xuong`
--
ALTER TABLE `phan_cong_ke_hoach_xuong`
  ADD PRIMARY KEY (`IdPhanCong`),
  ADD KEY `FKPCKHX_KeHoach` (`IdKeHoachSanXuatXuong`),
  ADD KEY `FKPCKHX_NhanVien` (`IdNhanVien`),
  ADD KEY `FKPCKHX_CaLam` (`IdCaLamViec`);

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
-- Chỉ mục cho bảng `xuong_nhan_vien`
--
ALTER TABLE `xuong_nhan_vien`
  ADD PRIMARY KEY (`IdXuong`,`IdNhanVien`,`VaiTro`),
  ADD KEY `FKXNV_NhanVien` (`IdNhanVien`);

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
-- Các ràng buộc cho bảng `phan_cong_ke_hoach_xuong`
--
ALTER TABLE `phan_cong_ke_hoach_xuong`
  ADD CONSTRAINT `FKPCKHX_KeHoach` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`),
  ADD CONSTRAINT `FKPCKHX_NhanVien` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `FKPCKHX_CaLam` FOREIGN KEY (`IdCaLamViec`) REFERENCES `ca_lam` (`IdCaLamViec`);

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
  ADD CONSTRAINT `nhan_vien_ibfk_1` FOREIGN KEY (`idXuong`) REFERENCES `xuong` (`IdXuong`),
  ADD CONSTRAINT `FKNHAN_VIEN_VAI_TRO` FOREIGN KEY (`IdVaiTro`) REFERENCES `vai_tro` (`IdVaiTro`);

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
-- Các ràng buộc cho bảng `xuong_nhan_vien`
--
ALTER TABLE `xuong_nhan_vien`
  ADD CONSTRAINT `FKXNV_NhanVien` FOREIGN KEY (`IdNhanVien`) REFERENCES `nhan_vien` (`IdNhanVien`),
  ADD CONSTRAINT `FKXNV_Xuong` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`);

--
-- Các ràng buộc cho bảng `xuong_cau_hinh_san_pham`
--
ALTER TABLE `xuong_cau_hinh_san_pham`
  ADD CONSTRAINT `FKXCHSP_CAU_HINH` FOREIGN KEY (`IdCauHinh`) REFERENCES `cau_hinh_san_pham` (`IdCauHinh`),
  ADD CONSTRAINT `FKXCHSP_SANPHAM` FOREIGN KEY (`IdSanPham`) REFERENCES `san_pham` (`IdSanPham`),
  ADD CONSTRAINT `FKXCHSP_XUONG` FOREIGN KEY (`IdXuong`) REFERENCES `xuong` (`IdXuong`);

--
-- Các ràng buộc cho bảng `yeu_cau_xuat_kho`
--
ALTER TABLE `yeu_cau_xuat_kho`
  ADD CONSTRAINT `FKYCK_KeHoachXuong` FOREIGN KEY (`IdKeHoachSanXuatXuong`) REFERENCES `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--
-- Dữ liệu mẫu phục vụ kiểm thử
--
SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `xuong` (`IdXuong`, `TenXuong`, `DiaDiem`, `NgayThanhLap`, `SlThietBi`, `SlNhanVien`, `SoLuongCongNhan`, `TenQuyTrinh`, `CongSuatToiDa`, `CongSuatDangSuDung`, `LoaiXuong`, `TrangThai`, `MoTa`, `XUONGTRUONG_IdNhanVien`) VALUES
('XUONG01', 'Xưởng Lắp Ráp A', 'Bình Dương', '2018-05-10', 40, 25, 15, 'Lắp ráp linh kiện', 500, 320, 'Lắp ráp', 'Hoạt động', 'Xưởng lắp ráp bàn phím cơ', 'NV_XU01'),
('XUONG02', 'Xưởng Hoàn Thiện B', 'Bình Dương', '2019-09-20', 30, 20, 12, 'Hoàn thiện & QC', 400, 250, 'Hoàn thiện', 'Hoạt động', 'Xưởng hoàn thiện và đóng gói', 'NV_XU02');

INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`, `IdVaiTro`) VALUES
('NV_ADMIN', 'Nguyễn Văn An', '1985-02-15', 1, 'Quản trị hệ thống', 4, 'Đang làm việc', 'Thủ Đức, TP.HCM', '2015-06-01 08:00:00', NULL, NULL, 'VT_ADMIN'),
('NV_GD01', 'Trần Thị Minh', '1979-08-22', 0, 'Giám đốc điều hành', 6, 'Đang làm việc', 'Quận 1, TP.HCM', '2012-04-15 08:00:00', NULL, NULL, 'VT_BAN_GIAM_DOC'),
('NV_XU01', 'Lê Quốc Huy', '1987-03-11', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thuận An, Bình Dương', '2016-07-01 08:00:00', NULL, 'XUONG01', 'VT_QUANLY_XUONG'),
('NV_XU02', 'Phạm Thu Hà', '1988-11-05', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Dĩ An, Bình Dương', '2017-03-01 08:00:00', NULL, 'XUONG02', 'VT_QUANLY_XUONG'),
('NV_KT01', 'Đặng Thị Hạnh', '1986-07-19', 0, 'Kế toán tổng hợp', 4, 'Đang làm việc', 'Phú Nhuận, TP.HCM', '2018-02-12 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV_KHO01', 'Vũ Minh Tuấn', '1989-01-30', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Thuận An, Bình Dương', '2019-01-05 08:00:00', NULL, 'XUONG01', 'VT_KHO_TRUONG'),
('NV_KHO02', 'Ngô Hải Yến', '1990-05-12', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Dĩ An, Bình Dương', '2019-08-12 08:00:00', NULL, 'XUONG02', 'VT_KHO_TRUONG'),
('NV_KNV01', 'Bùi Văn Phúc', '1994-02-17', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2020-04-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV_KD01', 'Đỗ Thanh Mai', '1991-09-09', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'Quận 7, TP.HCM', '2019-05-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV_QC01', 'Hoàng Gia Bảo', '1992-12-02', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2020-09-15 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV_SX01', 'Phan Quốc Thịnh', '1995-06-21', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2021-02-01 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV_SX02', 'Nguyễn Thảo Vy', '1996-10-13', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Dĩ An, Bình Dương', '2021-06-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV101', 'Nhân viên 101', '1980-01-01', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2010-01-01 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV102', 'Nhân viên 102', '1981-02-02', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2011-02-02 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV103', 'Nhân viên 103', '1982-03-03', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2012-03-03 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV104', 'Nhân viên 104', '1983-04-04', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2013-04-04 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV105', 'Nhân viên 105', '1984-05-05', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2014-05-05 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV106', 'Nhân viên 106', '1985-06-06', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2015-06-06 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV107', 'Nhân viên 107', '1986-07-07', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2016-07-07 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV108', 'Nhân viên 108', '1987-08-08', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2017-08-08 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV109', 'Nhân viên 109', '1988-09-09', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2018-09-09 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV110', 'Nhân viên 110', '1989-10-10', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2019-10-10 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV111', 'Nhân viên 111', '1990-11-11', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2020-11-11 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV112', 'Nhân viên 112', '1991-12-12', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2021-12-12 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV113', 'Nhân viên 113', '1992-01-13', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2010-01-13 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV114', 'Nhân viên 114', '1993-02-14', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2011-02-14 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV115', 'Nhân viên 115', '1994-03-15', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2012-03-15 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV116', 'Nhân viên 116', '1995-04-16', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2013-04-16 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV117', 'Nhân viên 117', '1996-05-17', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2014-05-17 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV118', 'Nhân viên 118', '1997-06-18', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2015-06-18 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV119', 'Nhân viên 119', '1998-07-19', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2016-07-19 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV120', 'Nhân viên 120', '1999-08-20', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2017-08-20 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV121', 'Nhân viên 121', '1980-09-21', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2018-09-21 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV122', 'Nhân viên 122', '1981-10-22', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2019-10-22 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV123', 'Nhân viên 123', '1982-11-23', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2020-11-23 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV124', 'Nhân viên 124', '1983-12-24', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2021-12-24 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV125', 'Nhân viên 125', '1984-01-25', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2010-01-25 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV126', 'Nhân viên 126', '1985-02-26', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2011-02-26 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV127', 'Nhân viên 127', '1986-03-27', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2012-03-27 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV128', 'Nhân viên 128', '1987-04-01', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2013-04-01 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV129', 'Nhân viên 129', '1988-05-02', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2014-05-02 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV130', 'Nhân viên 130', '1989-06-03', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2015-06-03 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV131', 'Nhân viên 131', '1990-07-04', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2016-07-04 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV132', 'Nhân viên 132', '1991-08-05', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2017-08-05 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV133', 'Nhân viên 133', '1992-09-06', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2018-09-06 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV134', 'Nhân viên 134', '1993-10-07', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2019-10-07 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV135', 'Nhân viên 135', '1994-11-08', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2020-11-08 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV136', 'Nhân viên 136', '1995-12-09', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2021-12-09 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV137', 'Nhân viên 137', '1996-01-10', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2010-01-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV138', 'Nhân viên 138', '1997-02-11', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2011-02-11 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV139', 'Nhân viên 139', '1998-03-12', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2012-03-12 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV140', 'Nhân viên 140', '1999-04-13', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2013-04-13 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV141', 'Nhân viên 141', '1980-05-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2014-05-14 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV142', 'Nhân viên 142', '1981-06-15', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2015-06-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV143', 'Nhân viên 143', '1982-07-16', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2016-07-16 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV144', 'Nhân viên 144', '1983-08-17', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2017-08-17 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV145', 'Nhân viên 145', '1984-09-18', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2018-09-18 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV146', 'Nhân viên 146', '1985-10-19', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2019-10-19 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV147', 'Nhân viên 147', '1986-11-20', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2020-11-20 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV148', 'Nhân viên 148', '1987-12-21', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2021-12-21 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV149', 'Nhân viên 149', '1988-01-22', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2010-01-22 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV150', 'Nhân viên 150', '1989-02-23', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2011-02-23 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV151', 'Nhân viên 151', '1990-03-24', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2012-03-24 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV152', 'Nhân viên 152', '1991-04-25', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2013-04-25 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV153', 'Nhân viên 153', '1992-05-26', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2014-05-26 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV154', 'Nhân viên 154', '1993-06-27', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2015-06-27 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV155', 'Nhân viên 155', '1994-07-01', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2016-07-01 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV156', 'Nhân viên 156', '1995-08-02', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2017-08-02 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV157', 'Nhân viên 157', '1996-09-03', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2018-09-03 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV158', 'Nhân viên 158', '1997-10-04', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2019-10-04 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV159', 'Nhân viên 159', '1998-11-05', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2020-11-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV160', 'Nhân viên 160', '1999-12-06', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2021-12-06 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV161', 'Nhân viên 161', '1980-01-07', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2010-01-07 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV162', 'Nhân viên 162', '1981-02-08', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2011-02-08 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV163', 'Nhân viên 163', '1982-03-09', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2012-03-09 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV164', 'Nhân viên 164', '1983-04-10', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2013-04-10 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV165', 'Nhân viên 165', '1984-05-11', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2014-05-11 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV166', 'Nhân viên 166', '1985-06-12', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2015-06-12 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV167', 'Nhân viên 167', '1986-07-13', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2016-07-13 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV168', 'Nhân viên 168', '1987-08-14', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2017-08-14 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV169', 'Nhân viên 169', '1988-09-15', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2018-09-15 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV170', 'Nhân viên 170', '1989-10-16', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2019-10-16 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV171', 'Nhân viên 171', '1990-11-17', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2020-11-17 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV172', 'Nhân viên 172', '1991-12-18', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2021-12-18 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV173', 'Nhân viên 173', '1992-01-19', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2010-01-19 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV174', 'Nhân viên 174', '1993-02-20', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2011-02-20 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV175', 'Nhân viên 175', '1994-03-21', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2012-03-21 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV176', 'Nhân viên 176', '1995-04-22', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2013-04-22 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV177', 'Nhân viên 177', '1996-05-23', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2014-05-23 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV178', 'Nhân viên 178', '1997-06-24', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2015-06-24 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV179', 'Nhân viên 179', '1998-07-25', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2016-07-25 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV180', 'Nhân viên 180', '1999-08-26', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2017-08-26 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV181', 'Nhân viên 181', '1980-09-27', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2018-09-27 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV182', 'Nhân viên 182', '1981-10-01', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2019-10-01 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV183', 'Nhân viên 183', '1982-11-02', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2020-11-02 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV184', 'Nhân viên 184', '1983-12-03', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2021-12-03 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV185', 'Nhân viên 185', '1984-01-04', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2010-01-04 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV186', 'Nhân viên 186', '1985-02-05', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2011-02-05 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV187', 'Nhân viên 187', '1986-03-06', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2012-03-06 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV188', 'Nhân viên 188', '1987-04-07', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2013-04-07 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV189', 'Nhân viên 189', '1988-05-08', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2014-05-08 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV190', 'Nhân viên 190', '1989-06-09', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2015-06-09 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV191', 'Nhân viên 191', '1990-07-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2016-07-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV192', 'Nhân viên 192', '1991-08-11', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2017-08-11 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV193', 'Nhân viên 193', '1992-09-12', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2018-09-12 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV194', 'Nhân viên 194', '1993-10-13', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2019-10-13 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV195', 'Nhân viên 195', '1994-11-14', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2020-11-14 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV196', 'Nhân viên 196', '1995-12-15', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2021-12-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV197', 'Nhân viên 197', '1996-01-16', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2010-01-16 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV198', 'Nhân viên 198', '1997-02-17', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2011-02-17 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV199', 'Nhân viên 199', '1998-03-18', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2012-03-18 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV200', 'Nhân viên 200', '1999-04-19', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2013-04-19 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV201', 'Nhân viên 201', '1980-05-20', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2014-05-20 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV202', 'Nhân viên 202', '1981-06-21', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2015-06-21 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV203', 'Nhân viên 203', '1982-07-22', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2016-07-22 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV204', 'Nhân viên 204', '1983-08-23', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2017-08-23 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV205', 'Nhân viên 205', '1984-09-24', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2018-09-24 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV206', 'Nhân viên 206', '1985-10-25', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2019-10-25 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV207', 'Nhân viên 207', '1986-11-26', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2020-11-26 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV208', 'Nhân viên 208', '1987-12-27', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2021-12-27 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV209', 'Nhân viên 209', '1988-01-01', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2010-01-01 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV210', 'Nhân viên 210', '1989-02-02', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2011-02-02 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV211', 'Nhân viên 211', '1990-03-03', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2012-03-03 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV212', 'Nhân viên 212', '1991-04-04', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2013-04-04 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV213', 'Nhân viên 213', '1992-05-05', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2014-05-05 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV214', 'Nhân viên 214', '1993-06-06', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2015-06-06 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV215', 'Nhân viên 215', '1994-07-07', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2016-07-07 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV216', 'Nhân viên 216', '1995-08-08', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2017-08-08 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV217', 'Nhân viên 217', '1996-09-09', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2018-09-09 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV218', 'Nhân viên 218', '1997-10-10', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2019-10-10 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV219', 'Nhân viên 219', '1998-11-11', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2020-11-11 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV220', 'Nhân viên 220', '1999-12-12', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2021-12-12 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV221', 'Nhân viên 221', '1980-01-13', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2010-01-13 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV222', 'Nhân viên 222', '1981-02-14', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2011-02-14 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV223', 'Nhân viên 223', '1982-03-15', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2012-03-15 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV224', 'Nhân viên 224', '1983-04-16', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2013-04-16 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV225', 'Nhân viên 225', '1984-05-17', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2014-05-17 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV226', 'Nhân viên 226', '1985-06-18', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2015-06-18 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV227', 'Nhân viên 227', '1986-07-19', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2016-07-19 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV228', 'Nhân viên 228', '1987-08-20', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2017-08-20 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV229', 'Nhân viên 229', '1988-09-21', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2018-09-21 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV230', 'Nhân viên 230', '1989-10-22', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2019-10-22 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV231', 'Nhân viên 231', '1990-11-23', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2020-11-23 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV232', 'Nhân viên 232', '1991-12-24', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2021-12-24 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV233', 'Nhân viên 233', '1992-01-25', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2010-01-25 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV234', 'Nhân viên 234', '1993-02-26', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2011-02-26 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV235', 'Nhân viên 235', '1994-03-27', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2012-03-27 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV236', 'Nhân viên 236', '1995-04-01', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2013-04-01 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV237', 'Nhân viên 237', '1996-05-02', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2014-05-02 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV238', 'Nhân viên 238', '1997-06-03', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2015-06-03 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV239', 'Nhân viên 239', '1998-07-04', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2016-07-04 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV240', 'Nhân viên 240', '1999-08-05', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2017-08-05 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV241', 'Nhân viên 241', '1980-09-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2018-09-06 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV242', 'Nhân viên 242', '1981-10-07', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2019-10-07 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV243', 'Nhân viên 243', '1982-11-08', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2020-11-08 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV244', 'Nhân viên 244', '1983-12-09', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2021-12-09 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV245', 'Nhân viên 245', '1984-01-10', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2010-01-10 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV246', 'Nhân viên 246', '1985-02-11', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2011-02-11 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV247', 'Nhân viên 247', '1986-03-12', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2012-03-12 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV248', 'Nhân viên 248', '1987-04-13', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2013-04-13 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV249', 'Nhân viên 249', '1988-05-14', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2014-05-14 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV250', 'Nhân viên 250', '1989-06-15', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2015-06-15 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV251', 'Nhân viên 251', '1990-07-16', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2016-07-16 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV252', 'Nhân viên 252', '1991-08-17', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2017-08-17 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV253', 'Nhân viên 253', '1992-09-18', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2018-09-18 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV254', 'Nhân viên 254', '1993-10-19', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2019-10-19 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV255', 'Nhân viên 255', '1994-11-20', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2020-11-20 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV256', 'Nhân viên 256', '1995-12-21', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2021-12-21 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV257', 'Nhân viên 257', '1996-01-22', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2010-01-22 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV258', 'Nhân viên 258', '1997-02-23', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2011-02-23 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV259', 'Nhân viên 259', '1998-03-24', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2012-03-24 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV260', 'Nhân viên 260', '1999-04-25', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2013-04-25 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV261', 'Nhân viên 261', '1980-05-26', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2014-05-26 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV262', 'Nhân viên 262', '1981-06-27', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2015-06-27 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV263', 'Nhân viên 263', '1982-07-01', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2016-07-01 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV264', 'Nhân viên 264', '1983-08-02', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2017-08-02 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV265', 'Nhân viên 265', '1984-09-03', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2018-09-03 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV266', 'Nhân viên 266', '1985-10-04', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2019-10-04 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV267', 'Nhân viên 267', '1986-11-05', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2020-11-05 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV268', 'Nhân viên 268', '1987-12-06', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2021-12-06 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV269', 'Nhân viên 269', '1988-01-07', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2010-01-07 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV270', 'Nhân viên 270', '1989-02-08', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2011-02-08 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV271', 'Nhân viên 271', '1990-03-09', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2012-03-09 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV272', 'Nhân viên 272', '1991-04-10', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2013-04-10 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV273', 'Nhân viên 273', '1992-05-11', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2014-05-11 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV274', 'Nhân viên 274', '1993-06-12', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2015-06-12 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV275', 'Nhân viên 275', '1994-07-13', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2016-07-13 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV276', 'Nhân viên 276', '1995-08-14', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2017-08-14 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV277', 'Nhân viên 277', '1996-09-15', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2018-09-15 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV278', 'Nhân viên 278', '1997-10-16', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2019-10-16 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV279', 'Nhân viên 279', '1998-11-17', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2020-11-17 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV280', 'Nhân viên 280', '1999-12-18', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2021-12-18 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV281', 'Nhân viên 281', '1980-01-19', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2010-01-19 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV282', 'Nhân viên 282', '1981-02-20', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2011-02-20 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV283', 'Nhân viên 283', '1982-03-21', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2012-03-21 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV284', 'Nhân viên 284', '1983-04-22', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2013-04-22 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV285', 'Nhân viên 285', '1984-05-23', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2014-05-23 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV286', 'Nhân viên 286', '1985-06-24', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2015-06-24 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV287', 'Nhân viên 287', '1986-07-25', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2016-07-25 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV288', 'Nhân viên 288', '1987-08-26', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2017-08-26 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV289', 'Nhân viên 289', '1988-09-27', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2018-09-27 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV290', 'Nhân viên 290', '1989-10-01', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2019-10-01 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV291', 'Nhân viên 291', '1990-11-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2020-11-02 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV292', 'Nhân viên 292', '1991-12-03', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2021-12-03 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV293', 'Nhân viên 293', '1992-01-04', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2010-01-04 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV294', 'Nhân viên 294', '1993-02-05', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2011-02-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV295', 'Nhân viên 295', '1994-03-06', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2012-03-06 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV296', 'Nhân viên 296', '1995-04-07', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2013-04-07 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV297', 'Nhân viên 297', '1996-05-08', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2014-05-08 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV298', 'Nhân viên 298', '1997-06-09', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2015-06-09 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV299', 'Nhân viên 299', '1998-07-10', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2016-07-10 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV300', 'Nhân viên 300', '1999-08-11', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2017-08-11 08:00:00', NULL, NULL, 'VT_KETOAN');

INSERT INTO `xuong_nhan_vien` (`IdXuong`, `IdNhanVien`, `VaiTro`) VALUES
('XUONG01', 'NV_XU01', 'Xưởng trưởng'),
('XUONG01', 'NV_QC01', 'Kiểm soát chất lượng'),
('XUONG01', 'NV_SX01', 'Nhân viên sản xuất'),
('XUONG01', 'NV_KNV01', 'Nhân viên kho'),
('XUONG02', 'NV_XU02', 'Xưởng trưởng'),
('XUONG02', 'NV_SX02', 'Nhân viên sản xuất'),
('XUONG02', 'NV_KHO02', 'Kho trưởng');

INSERT INTO `nguoi_dung` (`IdNguoiDung`, `TenDangNhap`, `MatKhau`, `TrangThai`, `IdNhanVien`, `IdVaiTro`) VALUES
('ND_ADMIN', 'admin', '123456', 'Hoạt động', 'NV_ADMIN', 'VT_ADMIN'),
('ND_GD01', 'giamdoc', '123456', 'Hoạt động', 'NV_GD01', 'VT_BAN_GIAM_DOC'),
('ND_XU01', 'xuong_a', '123456', 'Hoạt động', 'NV_XU01', 'VT_QUANLY_XUONG'),
('ND_XU02', 'xuong_b', '123456', 'Hoạt động', 'NV_XU02', 'VT_QUANLY_XUONG'),
('ND_KT01', 'ketoan', '123456', 'Hoạt động', 'NV_KT01', 'VT_KETOAN'),
('ND_KHO01', 'kho_a', '123456', 'Hoạt động', 'NV_KHO01', 'VT_KHO_TRUONG'),
('ND_KHO02', 'kho_b', '123456', 'Hoạt động', 'NV_KHO02', 'VT_KHO_TRUONG'),
('ND_KNV01', 'kho_nv', '123456', 'Hoạt động', 'NV_KNV01', 'VT_NHANVIEN_KHO'),
('ND_KD01', 'kinhdoanh', '123456', 'Hoạt động', 'NV_KD01', 'VT_KINH_DOANH'),
('ND_QC01', 'qc_a', '123456', 'Hoạt động', 'NV_QC01', 'VT_KIEM_SOAT_CL'),
('ND_SX01', 'sx_a', '123456', 'Hoạt động', 'NV_SX01', 'VT_NHANVIEN_SANXUAT'),
('ND_SX02', 'sx_b', '123456', 'Hoạt động', 'NV_SX02', 'VT_NHANVIEN_SANXUAT'),
('ND101', 'user101', '123456', 'Hoạt động', 'NV101', 'VT_NHANVIEN_SANXUAT'),
('ND102', 'user102', '123456', 'Hoạt động', 'NV102', 'VT_NHANVIEN_KHO'),
('ND103', 'user103', '123456', 'Hoạt động', 'NV103', 'VT_KIEM_SOAT_CL'),
('ND104', 'user104', '123456', 'Hoạt động', 'NV104', 'VT_KINH_DOANH'),
('ND105', 'user105', '123456', 'Hoạt động', 'NV105', 'VT_KETOAN'),
('ND106', 'user106', '123456', 'Hoạt động', 'NV106', 'VT_NHANVIEN_SANXUAT'),
('ND107', 'user107', '123456', 'Hoạt động', 'NV107', 'VT_NHANVIEN_KHO'),
('ND108', 'user108', '123456', 'Hoạt động', 'NV108', 'VT_KIEM_SOAT_CL'),
('ND109', 'user109', '123456', 'Hoạt động', 'NV109', 'VT_KINH_DOANH'),
('ND110', 'user110', '123456', 'Hoạt động', 'NV110', 'VT_KETOAN'),
('ND111', 'user111', '123456', 'Hoạt động', 'NV111', 'VT_NHANVIEN_SANXUAT'),
('ND112', 'user112', '123456', 'Hoạt động', 'NV112', 'VT_NHANVIEN_KHO'),
('ND113', 'user113', '123456', 'Hoạt động', 'NV113', 'VT_KIEM_SOAT_CL'),
('ND114', 'user114', '123456', 'Hoạt động', 'NV114', 'VT_KINH_DOANH'),
('ND115', 'user115', '123456', 'Hoạt động', 'NV115', 'VT_KETOAN'),
('ND116', 'user116', '123456', 'Hoạt động', 'NV116', 'VT_NHANVIEN_SANXUAT'),
('ND117', 'user117', '123456', 'Hoạt động', 'NV117', 'VT_NHANVIEN_KHO'),
('ND118', 'user118', '123456', 'Hoạt động', 'NV118', 'VT_KIEM_SOAT_CL'),
('ND119', 'user119', '123456', 'Hoạt động', 'NV119', 'VT_KINH_DOANH'),
('ND120', 'user120', '123456', 'Hoạt động', 'NV120', 'VT_KETOAN'),
('ND121', 'user121', '123456', 'Hoạt động', 'NV121', 'VT_NHANVIEN_SANXUAT'),
('ND122', 'user122', '123456', 'Hoạt động', 'NV122', 'VT_NHANVIEN_KHO'),
('ND123', 'user123', '123456', 'Hoạt động', 'NV123', 'VT_KIEM_SOAT_CL'),
('ND124', 'user124', '123456', 'Hoạt động', 'NV124', 'VT_KINH_DOANH'),
('ND125', 'user125', '123456', 'Hoạt động', 'NV125', 'VT_KETOAN'),
('ND126', 'user126', '123456', 'Hoạt động', 'NV126', 'VT_NHANVIEN_SANXUAT'),
('ND127', 'user127', '123456', 'Hoạt động', 'NV127', 'VT_NHANVIEN_KHO'),
('ND128', 'user128', '123456', 'Hoạt động', 'NV128', 'VT_KIEM_SOAT_CL'),
('ND129', 'user129', '123456', 'Hoạt động', 'NV129', 'VT_KINH_DOANH'),
('ND130', 'user130', '123456', 'Hoạt động', 'NV130', 'VT_KETOAN'),
('ND131', 'user131', '123456', 'Hoạt động', 'NV131', 'VT_NHANVIEN_SANXUAT'),
('ND132', 'user132', '123456', 'Hoạt động', 'NV132', 'VT_NHANVIEN_KHO'),
('ND133', 'user133', '123456', 'Hoạt động', 'NV133', 'VT_KIEM_SOAT_CL'),
('ND134', 'user134', '123456', 'Hoạt động', 'NV134', 'VT_KINH_DOANH'),
('ND135', 'user135', '123456', 'Hoạt động', 'NV135', 'VT_KETOAN'),
('ND136', 'user136', '123456', 'Hoạt động', 'NV136', 'VT_NHANVIEN_SANXUAT'),
('ND137', 'user137', '123456', 'Hoạt động', 'NV137', 'VT_NHANVIEN_KHO'),
('ND138', 'user138', '123456', 'Hoạt động', 'NV138', 'VT_KIEM_SOAT_CL'),
('ND139', 'user139', '123456', 'Hoạt động', 'NV139', 'VT_KINH_DOANH'),
('ND140', 'user140', '123456', 'Hoạt động', 'NV140', 'VT_KETOAN'),
('ND141', 'user141', '123456', 'Hoạt động', 'NV141', 'VT_NHANVIEN_SANXUAT'),
('ND142', 'user142', '123456', 'Hoạt động', 'NV142', 'VT_NHANVIEN_KHO'),
('ND143', 'user143', '123456', 'Hoạt động', 'NV143', 'VT_KIEM_SOAT_CL'),
('ND144', 'user144', '123456', 'Hoạt động', 'NV144', 'VT_KINH_DOANH'),
('ND145', 'user145', '123456', 'Hoạt động', 'NV145', 'VT_KETOAN'),
('ND146', 'user146', '123456', 'Hoạt động', 'NV146', 'VT_NHANVIEN_SANXUAT'),
('ND147', 'user147', '123456', 'Hoạt động', 'NV147', 'VT_NHANVIEN_KHO'),
('ND148', 'user148', '123456', 'Hoạt động', 'NV148', 'VT_KIEM_SOAT_CL'),
('ND149', 'user149', '123456', 'Hoạt động', 'NV149', 'VT_KINH_DOANH'),
('ND150', 'user150', '123456', 'Hoạt động', 'NV150', 'VT_KETOAN'),
('ND151', 'user151', '123456', 'Hoạt động', 'NV151', 'VT_NHANVIEN_SANXUAT'),
('ND152', 'user152', '123456', 'Hoạt động', 'NV152', 'VT_NHANVIEN_KHO'),
('ND153', 'user153', '123456', 'Hoạt động', 'NV153', 'VT_KIEM_SOAT_CL'),
('ND154', 'user154', '123456', 'Hoạt động', 'NV154', 'VT_KINH_DOANH'),
('ND155', 'user155', '123456', 'Hoạt động', 'NV155', 'VT_KETOAN'),
('ND156', 'user156', '123456', 'Hoạt động', 'NV156', 'VT_NHANVIEN_SANXUAT'),
('ND157', 'user157', '123456', 'Hoạt động', 'NV157', 'VT_NHANVIEN_KHO'),
('ND158', 'user158', '123456', 'Hoạt động', 'NV158', 'VT_KIEM_SOAT_CL'),
('ND159', 'user159', '123456', 'Hoạt động', 'NV159', 'VT_KINH_DOANH'),
('ND160', 'user160', '123456', 'Hoạt động', 'NV160', 'VT_KETOAN'),
('ND161', 'user161', '123456', 'Hoạt động', 'NV161', 'VT_NHANVIEN_SANXUAT'),
('ND162', 'user162', '123456', 'Hoạt động', 'NV162', 'VT_NHANVIEN_KHO'),
('ND163', 'user163', '123456', 'Hoạt động', 'NV163', 'VT_KIEM_SOAT_CL'),
('ND164', 'user164', '123456', 'Hoạt động', 'NV164', 'VT_KINH_DOANH'),
('ND165', 'user165', '123456', 'Hoạt động', 'NV165', 'VT_KETOAN'),
('ND166', 'user166', '123456', 'Hoạt động', 'NV166', 'VT_NHANVIEN_SANXUAT'),
('ND167', 'user167', '123456', 'Hoạt động', 'NV167', 'VT_NHANVIEN_KHO'),
('ND168', 'user168', '123456', 'Hoạt động', 'NV168', 'VT_KIEM_SOAT_CL'),
('ND169', 'user169', '123456', 'Hoạt động', 'NV169', 'VT_KINH_DOANH'),
('ND170', 'user170', '123456', 'Hoạt động', 'NV170', 'VT_KETOAN'),
('ND171', 'user171', '123456', 'Hoạt động', 'NV171', 'VT_NHANVIEN_SANXUAT'),
('ND172', 'user172', '123456', 'Hoạt động', 'NV172', 'VT_NHANVIEN_KHO'),
('ND173', 'user173', '123456', 'Hoạt động', 'NV173', 'VT_KIEM_SOAT_CL'),
('ND174', 'user174', '123456', 'Hoạt động', 'NV174', 'VT_KINH_DOANH'),
('ND175', 'user175', '123456', 'Hoạt động', 'NV175', 'VT_KETOAN'),
('ND176', 'user176', '123456', 'Hoạt động', 'NV176', 'VT_NHANVIEN_SANXUAT'),
('ND177', 'user177', '123456', 'Hoạt động', 'NV177', 'VT_NHANVIEN_KHO'),
('ND178', 'user178', '123456', 'Hoạt động', 'NV178', 'VT_KIEM_SOAT_CL'),
('ND179', 'user179', '123456', 'Hoạt động', 'NV179', 'VT_KINH_DOANH'),
('ND180', 'user180', '123456', 'Hoạt động', 'NV180', 'VT_KETOAN'),
('ND181', 'user181', '123456', 'Hoạt động', 'NV181', 'VT_NHANVIEN_SANXUAT'),
('ND182', 'user182', '123456', 'Hoạt động', 'NV182', 'VT_NHANVIEN_KHO'),
('ND183', 'user183', '123456', 'Hoạt động', 'NV183', 'VT_KIEM_SOAT_CL'),
('ND184', 'user184', '123456', 'Hoạt động', 'NV184', 'VT_KINH_DOANH'),
('ND185', 'user185', '123456', 'Hoạt động', 'NV185', 'VT_KETOAN'),
('ND186', 'user186', '123456', 'Hoạt động', 'NV186', 'VT_NHANVIEN_SANXUAT'),
('ND187', 'user187', '123456', 'Hoạt động', 'NV187', 'VT_NHANVIEN_KHO'),
('ND188', 'user188', '123456', 'Hoạt động', 'NV188', 'VT_KIEM_SOAT_CL'),
('ND189', 'user189', '123456', 'Hoạt động', 'NV189', 'VT_KINH_DOANH'),
('ND190', 'user190', '123456', 'Hoạt động', 'NV190', 'VT_KETOAN'),
('ND191', 'user191', '123456', 'Hoạt động', 'NV191', 'VT_NHANVIEN_SANXUAT'),
('ND192', 'user192', '123456', 'Hoạt động', 'NV192', 'VT_NHANVIEN_KHO'),
('ND193', 'user193', '123456', 'Hoạt động', 'NV193', 'VT_KIEM_SOAT_CL'),
('ND194', 'user194', '123456', 'Hoạt động', 'NV194', 'VT_KINH_DOANH'),
('ND195', 'user195', '123456', 'Hoạt động', 'NV195', 'VT_KETOAN'),
('ND196', 'user196', '123456', 'Hoạt động', 'NV196', 'VT_NHANVIEN_SANXUAT'),
('ND197', 'user197', '123456', 'Hoạt động', 'NV197', 'VT_NHANVIEN_KHO'),
('ND198', 'user198', '123456', 'Hoạt động', 'NV198', 'VT_KIEM_SOAT_CL'),
('ND199', 'user199', '123456', 'Hoạt động', 'NV199', 'VT_KINH_DOANH'),
('ND200', 'user200', '123456', 'Hoạt động', 'NV200', 'VT_KETOAN'),
('ND201', 'user201', '123456', 'Hoạt động', 'NV201', 'VT_NHANVIEN_SANXUAT'),
('ND202', 'user202', '123456', 'Hoạt động', 'NV202', 'VT_NHANVIEN_KHO'),
('ND203', 'user203', '123456', 'Hoạt động', 'NV203', 'VT_KIEM_SOAT_CL'),
('ND204', 'user204', '123456', 'Hoạt động', 'NV204', 'VT_KINH_DOANH'),
('ND205', 'user205', '123456', 'Hoạt động', 'NV205', 'VT_KETOAN'),
('ND206', 'user206', '123456', 'Hoạt động', 'NV206', 'VT_NHANVIEN_SANXUAT'),
('ND207', 'user207', '123456', 'Hoạt động', 'NV207', 'VT_NHANVIEN_KHO'),
('ND208', 'user208', '123456', 'Hoạt động', 'NV208', 'VT_KIEM_SOAT_CL'),
('ND209', 'user209', '123456', 'Hoạt động', 'NV209', 'VT_KINH_DOANH'),
('ND210', 'user210', '123456', 'Hoạt động', 'NV210', 'VT_KETOAN'),
('ND211', 'user211', '123456', 'Hoạt động', 'NV211', 'VT_NHANVIEN_SANXUAT'),
('ND212', 'user212', '123456', 'Hoạt động', 'NV212', 'VT_NHANVIEN_KHO'),
('ND213', 'user213', '123456', 'Hoạt động', 'NV213', 'VT_KIEM_SOAT_CL'),
('ND214', 'user214', '123456', 'Hoạt động', 'NV214', 'VT_KINH_DOANH'),
('ND215', 'user215', '123456', 'Hoạt động', 'NV215', 'VT_KETOAN'),
('ND216', 'user216', '123456', 'Hoạt động', 'NV216', 'VT_NHANVIEN_SANXUAT'),
('ND217', 'user217', '123456', 'Hoạt động', 'NV217', 'VT_NHANVIEN_KHO'),
('ND218', 'user218', '123456', 'Hoạt động', 'NV218', 'VT_KIEM_SOAT_CL'),
('ND219', 'user219', '123456', 'Hoạt động', 'NV219', 'VT_KINH_DOANH'),
('ND220', 'user220', '123456', 'Hoạt động', 'NV220', 'VT_KETOAN'),
('ND221', 'user221', '123456', 'Hoạt động', 'NV221', 'VT_NHANVIEN_SANXUAT'),
('ND222', 'user222', '123456', 'Hoạt động', 'NV222', 'VT_NHANVIEN_KHO'),
('ND223', 'user223', '123456', 'Hoạt động', 'NV223', 'VT_KIEM_SOAT_CL'),
('ND224', 'user224', '123456', 'Hoạt động', 'NV224', 'VT_KINH_DOANH'),
('ND225', 'user225', '123456', 'Hoạt động', 'NV225', 'VT_KETOAN'),
('ND226', 'user226', '123456', 'Hoạt động', 'NV226', 'VT_NHANVIEN_SANXUAT'),
('ND227', 'user227', '123456', 'Hoạt động', 'NV227', 'VT_NHANVIEN_KHO'),
('ND228', 'user228', '123456', 'Hoạt động', 'NV228', 'VT_KIEM_SOAT_CL'),
('ND229', 'user229', '123456', 'Hoạt động', 'NV229', 'VT_KINH_DOANH'),
('ND230', 'user230', '123456', 'Hoạt động', 'NV230', 'VT_KETOAN'),
('ND231', 'user231', '123456', 'Hoạt động', 'NV231', 'VT_NHANVIEN_SANXUAT'),
('ND232', 'user232', '123456', 'Hoạt động', 'NV232', 'VT_NHANVIEN_KHO'),
('ND233', 'user233', '123456', 'Hoạt động', 'NV233', 'VT_KIEM_SOAT_CL'),
('ND234', 'user234', '123456', 'Hoạt động', 'NV234', 'VT_KINH_DOANH'),
('ND235', 'user235', '123456', 'Hoạt động', 'NV235', 'VT_KETOAN'),
('ND236', 'user236', '123456', 'Hoạt động', 'NV236', 'VT_NHANVIEN_SANXUAT'),
('ND237', 'user237', '123456', 'Hoạt động', 'NV237', 'VT_NHANVIEN_KHO'),
('ND238', 'user238', '123456', 'Hoạt động', 'NV238', 'VT_KIEM_SOAT_CL'),
('ND239', 'user239', '123456', 'Hoạt động', 'NV239', 'VT_KINH_DOANH'),
('ND240', 'user240', '123456', 'Hoạt động', 'NV240', 'VT_KETOAN'),
('ND241', 'user241', '123456', 'Hoạt động', 'NV241', 'VT_NHANVIEN_SANXUAT'),
('ND242', 'user242', '123456', 'Hoạt động', 'NV242', 'VT_NHANVIEN_KHO'),
('ND243', 'user243', '123456', 'Hoạt động', 'NV243', 'VT_KIEM_SOAT_CL'),
('ND244', 'user244', '123456', 'Hoạt động', 'NV244', 'VT_KINH_DOANH'),
('ND245', 'user245', '123456', 'Hoạt động', 'NV245', 'VT_KETOAN'),
('ND246', 'user246', '123456', 'Hoạt động', 'NV246', 'VT_NHANVIEN_SANXUAT'),
('ND247', 'user247', '123456', 'Hoạt động', 'NV247', 'VT_NHANVIEN_KHO'),
('ND248', 'user248', '123456', 'Hoạt động', 'NV248', 'VT_KIEM_SOAT_CL'),
('ND249', 'user249', '123456', 'Hoạt động', 'NV249', 'VT_KINH_DOANH'),
('ND250', 'user250', '123456', 'Hoạt động', 'NV250', 'VT_KETOAN'),
('ND251', 'user251', '123456', 'Hoạt động', 'NV251', 'VT_NHANVIEN_SANXUAT'),
('ND252', 'user252', '123456', 'Hoạt động', 'NV252', 'VT_NHANVIEN_KHO'),
('ND253', 'user253', '123456', 'Hoạt động', 'NV253', 'VT_KIEM_SOAT_CL'),
('ND254', 'user254', '123456', 'Hoạt động', 'NV254', 'VT_KINH_DOANH'),
('ND255', 'user255', '123456', 'Hoạt động', 'NV255', 'VT_KETOAN'),
('ND256', 'user256', '123456', 'Hoạt động', 'NV256', 'VT_NHANVIEN_SANXUAT'),
('ND257', 'user257', '123456', 'Hoạt động', 'NV257', 'VT_NHANVIEN_KHO'),
('ND258', 'user258', '123456', 'Hoạt động', 'NV258', 'VT_KIEM_SOAT_CL'),
('ND259', 'user259', '123456', 'Hoạt động', 'NV259', 'VT_KINH_DOANH'),
('ND260', 'user260', '123456', 'Hoạt động', 'NV260', 'VT_KETOAN'),
('ND261', 'user261', '123456', 'Hoạt động', 'NV261', 'VT_NHANVIEN_SANXUAT'),
('ND262', 'user262', '123456', 'Hoạt động', 'NV262', 'VT_NHANVIEN_KHO'),
('ND263', 'user263', '123456', 'Hoạt động', 'NV263', 'VT_KIEM_SOAT_CL'),
('ND264', 'user264', '123456', 'Hoạt động', 'NV264', 'VT_KINH_DOANH'),
('ND265', 'user265', '123456', 'Hoạt động', 'NV265', 'VT_KETOAN'),
('ND266', 'user266', '123456', 'Hoạt động', 'NV266', 'VT_NHANVIEN_SANXUAT'),
('ND267', 'user267', '123456', 'Hoạt động', 'NV267', 'VT_NHANVIEN_KHO'),
('ND268', 'user268', '123456', 'Hoạt động', 'NV268', 'VT_KIEM_SOAT_CL'),
('ND269', 'user269', '123456', 'Hoạt động', 'NV269', 'VT_KINH_DOANH'),
('ND270', 'user270', '123456', 'Hoạt động', 'NV270', 'VT_KETOAN'),
('ND271', 'user271', '123456', 'Hoạt động', 'NV271', 'VT_NHANVIEN_SANXUAT'),
('ND272', 'user272', '123456', 'Hoạt động', 'NV272', 'VT_NHANVIEN_KHO'),
('ND273', 'user273', '123456', 'Hoạt động', 'NV273', 'VT_KIEM_SOAT_CL'),
('ND274', 'user274', '123456', 'Hoạt động', 'NV274', 'VT_KINH_DOANH'),
('ND275', 'user275', '123456', 'Hoạt động', 'NV275', 'VT_KETOAN'),
('ND276', 'user276', '123456', 'Hoạt động', 'NV276', 'VT_NHANVIEN_SANXUAT'),
('ND277', 'user277', '123456', 'Hoạt động', 'NV277', 'VT_NHANVIEN_KHO'),
('ND278', 'user278', '123456', 'Hoạt động', 'NV278', 'VT_KIEM_SOAT_CL'),
('ND279', 'user279', '123456', 'Hoạt động', 'NV279', 'VT_KINH_DOANH'),
('ND280', 'user280', '123456', 'Hoạt động', 'NV280', 'VT_KETOAN'),
('ND281', 'user281', '123456', 'Hoạt động', 'NV281', 'VT_NHANVIEN_SANXUAT'),
('ND282', 'user282', '123456', 'Hoạt động', 'NV282', 'VT_NHANVIEN_KHO'),
('ND283', 'user283', '123456', 'Hoạt động', 'NV283', 'VT_KIEM_SOAT_CL'),
('ND284', 'user284', '123456', 'Hoạt động', 'NV284', 'VT_KINH_DOANH'),
('ND285', 'user285', '123456', 'Hoạt động', 'NV285', 'VT_KETOAN'),
('ND286', 'user286', '123456', 'Hoạt động', 'NV286', 'VT_NHANVIEN_SANXUAT'),
('ND287', 'user287', '123456', 'Hoạt động', 'NV287', 'VT_NHANVIEN_KHO'),
('ND288', 'user288', '123456', 'Hoạt động', 'NV288', 'VT_KIEM_SOAT_CL'),
('ND289', 'user289', '123456', 'Hoạt động', 'NV289', 'VT_KINH_DOANH'),
('ND290', 'user290', '123456', 'Hoạt động', 'NV290', 'VT_KETOAN'),
('ND291', 'user291', '123456', 'Hoạt động', 'NV291', 'VT_NHANVIEN_SANXUAT'),
('ND292', 'user292', '123456', 'Hoạt động', 'NV292', 'VT_NHANVIEN_KHO'),
('ND293', 'user293', '123456', 'Hoạt động', 'NV293', 'VT_KIEM_SOAT_CL'),
('ND294', 'user294', '123456', 'Hoạt động', 'NV294', 'VT_KINH_DOANH'),
('ND295', 'user295', '123456', 'Hoạt động', 'NV295', 'VT_KETOAN'),
('ND296', 'user296', '123456', 'Hoạt động', 'NV296', 'VT_NHANVIEN_SANXUAT'),
('ND297', 'user297', '123456', 'Hoạt động', 'NV297', 'VT_NHANVIEN_KHO'),
('ND298', 'user298', '123456', 'Hoạt động', 'NV298', 'VT_KIEM_SOAT_CL'),
('ND299', 'user299', '123456', 'Hoạt động', 'NV299', 'VT_KINH_DOANH'),
('ND300', 'user300', '123456', 'Hoạt động', 'NV300', 'VT_KETOAN');

INSERT INTO `kho` (`IdKho`, `TenKho`, `TenLoaiKho`, `DiaChi`, `TongSLLo`, `ThanhTien`, `TrangThai`, `TongSL`, `IdXuong`, `NHAN_VIEN_KHO_IdNhanVien`) VALUES
('KHO01', 'Kho Nguyên Liệu A', 'Kho nguyên liệu', 'Thuận An, Bình Dương', 5, 120000000, 'Đang hoạt động', 1900, 'XUONG01', 'NV_KHO01'),
('KHO02', 'Kho Thành Phẩm B', 'Kho thành phẩm', 'Dĩ An, Bình Dương', 3, 350000000, 'Đang hoạt động', 610, 'XUONG02', 'NV_KHO02');

INSERT INTO `san_pham` (`IdSanPham`, `TenSanPham`, `DonVi`, `GiaBan`, `MoTa`) VALUES
('SP001', 'MecaKey 75 Pro', 'bộ', 2500000, 'Bàn phím cơ 75% cho game thủ'),
('SP002', 'MecaKey 65 Lite', 'bộ', 1900000, 'Bàn phím cơ 65% gọn nhẹ'),
('SP003', 'Office Lite', 'bộ', 1200000, 'Bàn phím văn phòng yên tĩnh'),
('SPNL001', 'PCB 75%', 'tấm', 120000, 'Mainboard PCB 75%'),
('SPNL002', 'Switch Gateron Red', 'chiếc', 8000, 'Switch tuyến tính cho bàn phím cơ'),
('SPNL003', 'Keycap PBT', 'bộ', 150000, 'Keycap PBT double-shot'),
('SPNL004', 'Vỏ nhôm 75%', 'bộ', 300000, 'Case nhôm CNC 75%'),
('SPNL005', 'Foam cách âm', 'tấm', 20000, 'Foam cách âm cho bàn phím');

INSERT INTO `product_components` (`IdBOM`, `TenBOM`, `MoTa`, `IdSanPham`) VALUES
('BOM001', 'BOM MecaKey 75 Pro', 'Danh mục linh kiện MecaKey 75 Pro', 'SP001'),
('BOM002', 'BOM MecaKey 65 Lite', 'Danh mục linh kiện MecaKey 65 Lite', 'SP002'),
('BOM003', 'BOM Office Lite', 'Danh mục linh kiện Office Lite', 'SP003');

INSERT INTO `cau_hinh_san_pham` (`IdCauHinh`, `TenCauHinh`, `MoTa`, `GiaBan`, `IdSanPham`, `IdBOM`, `Keycap`, `Mainboard`, `Layout`, `SwitchType`, `CaseType`, `Foam`) VALUES
('CH001', 'MecaKey 75 Pro', 'Cấu hình cao cấp với case nhôm', 2500000, 'SP001', 'BOM001', 'PBT Double-shot', 'PCB 75% hot-swap', '75%', 'Gateron Red', 'Alu CNC', 'Poron'),
('CH002', 'MecaKey 65 Lite', 'Cấu hình 65% tối ưu chi phí', 1900000, 'SP002', 'BOM002', 'PBT', 'PCB 65%', '65%', 'Kailh Brown', 'ABS', 'EVA'),
('CH003', 'Office Lite Standard', 'Cấu hình văn phòng yên tĩnh', 1200000, 'SP003', 'BOM003', 'ABS', 'PCB fullsize', 'Fullsize', 'Outemu Silent', 'ABS', 'EVA');

INSERT INTO `lo` (`IdLo`, `TenLo`, `SoLuong`, `NgayTao`, `LoaiLo`, `IdSanPham`, `IdKho`) VALUES
('LO001', 'Lô SP001-0424', 200, '2024-04-02 08:00:00', 'Thành phẩm', 'SP001', 'KHO02'),
('LO002', 'Lô SP002-0424', 160, '2024-04-05 08:00:00', 'Thành phẩm', 'SP002', 'KHO02'),
('LO003', 'Lô SP003-0424', 300, '2024-04-07 08:00:00', 'Thành phẩm', 'SP003', 'KHO02'),
('LO004', 'Lô PCB 75% 03-2024', 400, '2024-03-20 08:00:00', 'Nguyên liệu', 'SPNL001', 'KHO01'),
('LO005', 'Lô Switch Gateron Red 03-2024', 600, '2024-03-22 08:00:00', 'Nguyên liệu', 'SPNL002', 'KHO01'),
('LO006', 'Lô Keycap PBT 03-2024', 500, '2024-03-23 08:00:00', 'Nguyên liệu', 'SPNL003', 'KHO01'),
('LO007', 'Lô Case nhôm 75% 03-2024', 300, '2024-03-24 08:00:00', 'Nguyên liệu', 'SPNL004', 'KHO01'),
('LO008', 'Lô Foam 75% 03-2024', 350, '2024-03-25 08:00:00', 'Nguyên liệu', 'SPNL005', 'KHO01');

INSERT INTO `nguyen_lieu` (`IdNguyenLieu`, `TenNL`, `SoLuong`, `DonVi`, `DonGian`, `TrangThai`, `NgaySanXuat`, `NgayHetHan`, `IdLo`) VALUES
('NL001', 'PCB 75%', 380, 'tấm', 120000, 'Đạt', '2024-03-20 00:00:00', '2026-03-20 00:00:00', 'LO004'),
('NL002', 'Switch Gateron Red', 580, 'chiếc', 8000, 'Đạt', '2024-03-22 00:00:00', '2026-03-22 00:00:00', 'LO005'),
('NL003', 'Keycap PBT', 480, 'bộ', 150000, 'Đạt', '2024-03-23 00:00:00', '2026-03-23 00:00:00', 'LO006'),
('NL004', 'Case nhôm 75%', 290, 'bộ', 300000, 'Đạt', '2024-03-24 00:00:00', '2027-03-24 00:00:00', 'LO007'),
('NL005', 'Foam cách âm', 330, 'tấm', 20000, 'Đạt', '2024-03-25 00:00:00', '2026-03-25 00:00:00', 'LO008');

INSERT INTO `cau_hinh_nguyen_lieu` (`IdCauHinhNguyenLieu`, `IdCauHinh`, `IdNguyenLieu`, `TyLeSoLuong`, `DinhMuc`, `Nhan`, `DonVi`) VALUES
('CHNL001', 'CH001', 'NL001', 1, 1, 'PCB', 'tấm'),
('CHNL002', 'CH001', 'NL002', 1, 84, 'Switch', 'chiếc'),
('CHNL003', 'CH001', 'NL003', 1, 1, 'Keycap', 'bộ'),
('CHNL004', 'CH001', 'NL004', 1, 1, 'Case', 'bộ'),
('CHNL005', 'CH001', 'NL005', 1, 1, 'Foam', 'tấm'),
('CHNL006', 'CH002', 'NL002', 1, 68, 'Switch', 'chiếc'),
('CHNL007', 'CH002', 'NL003', 1, 1, 'Keycap', 'bộ'),
('CHNL008', 'CH003', 'NL003', 1, 1, 'Keycap', 'bộ');

INSERT INTO `khach_hang` (`IdKhachHang`, `HoTen`, `TenCongTy`, `GioiTinh`, `DiaChi`, `SoLuongDonHang`, `SoDienThoai`, `Email`, `TongTien`, `LoaiKhachHang`) VALUES
('KH001', 'Nguyễn Hoài Nam', 'Công ty TechNova', 1, 'Quận 7, TP.HCM', 2, '0909001122', 'nam@technova.vn', 320000000, 'Doanh nghiệp'),
('KH002', 'Lưu Thị Thanh', 'Cửa hàng Gear Hub', 0, 'TP. Thủ Đức, TP.HCM', 1, '0911223344', 'thanh@gearhub.vn', 150000000, 'Đại lý');

INSERT INTO `don_hang` (`IdDonHang`, `YeuCau`, `TongTien`, `NgayLap`, `TrangThai`, `EmailLienHe`, `IdKhachHang`, `IdNguoiTao`) VALUES
('DH001', 'Yêu cầu layout 75% và case nhôm', 250000000, '2024-04-01', 'Đang sản xuất', 'nam@technova.vn', 'KH001', 'NV_KD01'),
('DH002', 'Đặt 65% layout cho đại lý', 150000000, '2024-04-03', 'Đã xác nhận', 'thanh@gearhub.vn', 'KH002', 'NV_KD01'),
('DH101', 'Đơn hàng bổ sung 101', 120000000, '2024-04-01', 'Đã xác nhận', 'contact101@example.com', 'KH001', 'NV_KD01'),
('DH102', 'Đơn hàng bổ sung 102', 125000000, '2024-04-02', 'Đang sản xuất', 'contact102@example.com', 'KH002', 'NV_KD01'),
('DH103', 'Đơn hàng bổ sung 103', 130000000, '2024-04-03', 'Đang sản xuất', 'contact103@example.com', 'KH001', 'NV_KD01'),
('DH104', 'Đơn hàng bổ sung 104', 135000000, '2024-04-04', 'Đã xác nhận', 'contact104@example.com', 'KH002', 'NV_KD01'),
('DH105', 'Đơn hàng bổ sung 105', 140000000, '2024-04-05', 'Đang sản xuất', 'contact105@example.com', 'KH001', 'NV_KD01'),
('DH106', 'Đơn hàng bổ sung 106', 145000000, '2024-04-06', 'Đang sản xuất', 'contact106@example.com', 'KH002', 'NV_KD01'),
('DH107', 'Đơn hàng bổ sung 107', 150000000, '2024-04-07', 'Đã xác nhận', 'contact107@example.com', 'KH001', 'NV_KD01'),
('DH108', 'Đơn hàng bổ sung 108', 155000000, '2024-04-08', 'Đang sản xuất', 'contact108@example.com', 'KH002', 'NV_KD01'),
('DH109', 'Đơn hàng bổ sung 109', 160000000, '2024-04-09', 'Đang sản xuất', 'contact109@example.com', 'KH001', 'NV_KD01'),
('DH110', 'Đơn hàng bổ sung 110', 165000000, '2024-04-10', 'Đã xác nhận', 'contact110@example.com', 'KH002', 'NV_KD01'),
('DH111', 'Đơn hàng bổ sung 111', 120000000, '2024-04-11', 'Đang sản xuất', 'contact111@example.com', 'KH001', 'NV_KD01'),
('DH112', 'Đơn hàng bổ sung 112', 125000000, '2024-04-12', 'Đang sản xuất', 'contact112@example.com', 'KH002', 'NV_KD01'),
('DH113', 'Đơn hàng bổ sung 113', 130000000, '2024-04-13', 'Đã xác nhận', 'contact113@example.com', 'KH001', 'NV_KD01'),
('DH114', 'Đơn hàng bổ sung 114', 135000000, '2024-04-14', 'Đang sản xuất', 'contact114@example.com', 'KH002', 'NV_KD01'),
('DH115', 'Đơn hàng bổ sung 115', 140000000, '2024-04-15', 'Đang sản xuất', 'contact115@example.com', 'KH001', 'NV_KD01'),
('DH116', 'Đơn hàng bổ sung 116', 145000000, '2024-04-16', 'Đã xác nhận', 'contact116@example.com', 'KH002', 'NV_KD01'),
('DH117', 'Đơn hàng bổ sung 117', 150000000, '2024-04-17', 'Đang sản xuất', 'contact117@example.com', 'KH001', 'NV_KD01'),
('DH118', 'Đơn hàng bổ sung 118', 155000000, '2024-04-18', 'Đang sản xuất', 'contact118@example.com', 'KH002', 'NV_KD01'),
('DH119', 'Đơn hàng bổ sung 119', 160000000, '2024-04-19', 'Đã xác nhận', 'contact119@example.com', 'KH001', 'NV_KD01'),
('DH120', 'Đơn hàng bổ sung 120', 165000000, '2024-04-20', 'Đang sản xuất', 'contact120@example.com', 'KH002', 'NV_KD01'),
('DH121', 'Đơn hàng bổ sung 121', 120000000, '2024-04-21', 'Đang sản xuất', 'contact121@example.com', 'KH001', 'NV_KD01'),
('DH122', 'Đơn hàng bổ sung 122', 125000000, '2024-04-22', 'Đã xác nhận', 'contact122@example.com', 'KH002', 'NV_KD01'),
('DH123', 'Đơn hàng bổ sung 123', 130000000, '2024-04-23', 'Đang sản xuất', 'contact123@example.com', 'KH001', 'NV_KD01'),
('DH124', 'Đơn hàng bổ sung 124', 135000000, '2024-04-24', 'Đang sản xuất', 'contact124@example.com', 'KH002', 'NV_KD01'),
('DH125', 'Đơn hàng bổ sung 125', 140000000, '2024-04-25', 'Đã xác nhận', 'contact125@example.com', 'KH001', 'NV_KD01'),
('DH126', 'Đơn hàng bổ sung 126', 145000000, '2024-04-26', 'Đang sản xuất', 'contact126@example.com', 'KH002', 'NV_KD01'),
('DH127', 'Đơn hàng bổ sung 127', 150000000, '2024-04-27', 'Đang sản xuất', 'contact127@example.com', 'KH001', 'NV_KD01'),
('DH128', 'Đơn hàng bổ sung 128', 155000000, '2024-04-28', 'Đã xác nhận', 'contact128@example.com', 'KH002', 'NV_KD01'),
('DH129', 'Đơn hàng bổ sung 129', 160000000, '2024-04-01', 'Đang sản xuất', 'contact129@example.com', 'KH001', 'NV_KD01'),
('DH130', 'Đơn hàng bổ sung 130', 165000000, '2024-04-02', 'Đang sản xuất', 'contact130@example.com', 'KH002', 'NV_KD01'),
('DH131', 'Đơn hàng bổ sung 131', 120000000, '2024-04-03', 'Đã xác nhận', 'contact131@example.com', 'KH001', 'NV_KD01'),
('DH132', 'Đơn hàng bổ sung 132', 125000000, '2024-04-04', 'Đang sản xuất', 'contact132@example.com', 'KH002', 'NV_KD01'),
('DH133', 'Đơn hàng bổ sung 133', 130000000, '2024-04-05', 'Đang sản xuất', 'contact133@example.com', 'KH001', 'NV_KD01'),
('DH134', 'Đơn hàng bổ sung 134', 135000000, '2024-04-06', 'Đã xác nhận', 'contact134@example.com', 'KH002', 'NV_KD01'),
('DH135', 'Đơn hàng bổ sung 135', 140000000, '2024-04-07', 'Đang sản xuất', 'contact135@example.com', 'KH001', 'NV_KD01'),
('DH136', 'Đơn hàng bổ sung 136', 145000000, '2024-04-08', 'Đang sản xuất', 'contact136@example.com', 'KH002', 'NV_KD01'),
('DH137', 'Đơn hàng bổ sung 137', 150000000, '2024-04-09', 'Đã xác nhận', 'contact137@example.com', 'KH001', 'NV_KD01'),
('DH138', 'Đơn hàng bổ sung 138', 155000000, '2024-04-10', 'Đang sản xuất', 'contact138@example.com', 'KH002', 'NV_KD01'),
('DH139', 'Đơn hàng bổ sung 139', 160000000, '2024-04-11', 'Đang sản xuất', 'contact139@example.com', 'KH001', 'NV_KD01'),
('DH140', 'Đơn hàng bổ sung 140', 165000000, '2024-04-12', 'Đã xác nhận', 'contact140@example.com', 'KH002', 'NV_KD01'),
('DH141', 'Đơn hàng bổ sung 141', 120000000, '2024-04-13', 'Đang sản xuất', 'contact141@example.com', 'KH001', 'NV_KD01'),
('DH142', 'Đơn hàng bổ sung 142', 125000000, '2024-04-14', 'Đang sản xuất', 'contact142@example.com', 'KH002', 'NV_KD01'),
('DH143', 'Đơn hàng bổ sung 143', 130000000, '2024-04-15', 'Đã xác nhận', 'contact143@example.com', 'KH001', 'NV_KD01'),
('DH144', 'Đơn hàng bổ sung 144', 135000000, '2024-04-16', 'Đang sản xuất', 'contact144@example.com', 'KH002', 'NV_KD01'),
('DH145', 'Đơn hàng bổ sung 145', 140000000, '2024-04-17', 'Đang sản xuất', 'contact145@example.com', 'KH001', 'NV_KD01'),
('DH146', 'Đơn hàng bổ sung 146', 145000000, '2024-04-18', 'Đã xác nhận', 'contact146@example.com', 'KH002', 'NV_KD01'),
('DH147', 'Đơn hàng bổ sung 147', 150000000, '2024-04-19', 'Đang sản xuất', 'contact147@example.com', 'KH001', 'NV_KD01'),
('DH148', 'Đơn hàng bổ sung 148', 155000000, '2024-04-20', 'Đang sản xuất', 'contact148@example.com', 'KH002', 'NV_KD01'),
('DH149', 'Đơn hàng bổ sung 149', 160000000, '2024-04-21', 'Đã xác nhận', 'contact149@example.com', 'KH001', 'NV_KD01'),
('DH150', 'Đơn hàng bổ sung 150', 165000000, '2024-04-22', 'Đang sản xuất', 'contact150@example.com', 'KH002', 'NV_KD01'),
('DH151', 'Đơn hàng bổ sung 151', 120000000, '2024-04-23', 'Đang sản xuất', 'contact151@example.com', 'KH001', 'NV_KD01'),
('DH152', 'Đơn hàng bổ sung 152', 125000000, '2024-04-24', 'Đã xác nhận', 'contact152@example.com', 'KH002', 'NV_KD01'),
('DH153', 'Đơn hàng bổ sung 153', 130000000, '2024-04-25', 'Đang sản xuất', 'contact153@example.com', 'KH001', 'NV_KD01'),
('DH154', 'Đơn hàng bổ sung 154', 135000000, '2024-04-26', 'Đang sản xuất', 'contact154@example.com', 'KH002', 'NV_KD01'),
('DH155', 'Đơn hàng bổ sung 155', 140000000, '2024-04-27', 'Đã xác nhận', 'contact155@example.com', 'KH001', 'NV_KD01'),
('DH156', 'Đơn hàng bổ sung 156', 145000000, '2024-04-28', 'Đang sản xuất', 'contact156@example.com', 'KH002', 'NV_KD01'),
('DH157', 'Đơn hàng bổ sung 157', 150000000, '2024-04-01', 'Đang sản xuất', 'contact157@example.com', 'KH001', 'NV_KD01'),
('DH158', 'Đơn hàng bổ sung 158', 155000000, '2024-04-02', 'Đã xác nhận', 'contact158@example.com', 'KH002', 'NV_KD01'),
('DH159', 'Đơn hàng bổ sung 159', 160000000, '2024-04-03', 'Đang sản xuất', 'contact159@example.com', 'KH001', 'NV_KD01'),
('DH160', 'Đơn hàng bổ sung 160', 165000000, '2024-04-04', 'Đang sản xuất', 'contact160@example.com', 'KH002', 'NV_KD01'),
('DH161', 'Đơn hàng bổ sung 161', 120000000, '2024-04-05', 'Đã xác nhận', 'contact161@example.com', 'KH001', 'NV_KD01'),
('DH162', 'Đơn hàng bổ sung 162', 125000000, '2024-04-06', 'Đang sản xuất', 'contact162@example.com', 'KH002', 'NV_KD01'),
('DH163', 'Đơn hàng bổ sung 163', 130000000, '2024-04-07', 'Đang sản xuất', 'contact163@example.com', 'KH001', 'NV_KD01'),
('DH164', 'Đơn hàng bổ sung 164', 135000000, '2024-04-08', 'Đã xác nhận', 'contact164@example.com', 'KH002', 'NV_KD01'),
('DH165', 'Đơn hàng bổ sung 165', 140000000, '2024-04-09', 'Đang sản xuất', 'contact165@example.com', 'KH001', 'NV_KD01'),
('DH166', 'Đơn hàng bổ sung 166', 145000000, '2024-04-10', 'Đang sản xuất', 'contact166@example.com', 'KH002', 'NV_KD01'),
('DH167', 'Đơn hàng bổ sung 167', 150000000, '2024-04-11', 'Đã xác nhận', 'contact167@example.com', 'KH001', 'NV_KD01'),
('DH168', 'Đơn hàng bổ sung 168', 155000000, '2024-04-12', 'Đang sản xuất', 'contact168@example.com', 'KH002', 'NV_KD01'),
('DH169', 'Đơn hàng bổ sung 169', 160000000, '2024-04-13', 'Đang sản xuất', 'contact169@example.com', 'KH001', 'NV_KD01'),
('DH170', 'Đơn hàng bổ sung 170', 165000000, '2024-04-14', 'Đã xác nhận', 'contact170@example.com', 'KH002', 'NV_KD01'),
('DH171', 'Đơn hàng bổ sung 171', 120000000, '2024-04-15', 'Đang sản xuất', 'contact171@example.com', 'KH001', 'NV_KD01'),
('DH172', 'Đơn hàng bổ sung 172', 125000000, '2024-04-16', 'Đang sản xuất', 'contact172@example.com', 'KH002', 'NV_KD01'),
('DH173', 'Đơn hàng bổ sung 173', 130000000, '2024-04-17', 'Đã xác nhận', 'contact173@example.com', 'KH001', 'NV_KD01'),
('DH174', 'Đơn hàng bổ sung 174', 135000000, '2024-04-18', 'Đang sản xuất', 'contact174@example.com', 'KH002', 'NV_KD01'),
('DH175', 'Đơn hàng bổ sung 175', 140000000, '2024-04-19', 'Đang sản xuất', 'contact175@example.com', 'KH001', 'NV_KD01'),
('DH176', 'Đơn hàng bổ sung 176', 145000000, '2024-04-20', 'Đã xác nhận', 'contact176@example.com', 'KH002', 'NV_KD01'),
('DH177', 'Đơn hàng bổ sung 177', 150000000, '2024-04-21', 'Đang sản xuất', 'contact177@example.com', 'KH001', 'NV_KD01'),
('DH178', 'Đơn hàng bổ sung 178', 155000000, '2024-04-22', 'Đang sản xuất', 'contact178@example.com', 'KH002', 'NV_KD01'),
('DH179', 'Đơn hàng bổ sung 179', 160000000, '2024-04-23', 'Đã xác nhận', 'contact179@example.com', 'KH001', 'NV_KD01'),
('DH180', 'Đơn hàng bổ sung 180', 165000000, '2024-04-24', 'Đang sản xuất', 'contact180@example.com', 'KH002', 'NV_KD01'),
('DH181', 'Đơn hàng bổ sung 181', 120000000, '2024-04-25', 'Đang sản xuất', 'contact181@example.com', 'KH001', 'NV_KD01'),
('DH182', 'Đơn hàng bổ sung 182', 125000000, '2024-04-26', 'Đã xác nhận', 'contact182@example.com', 'KH002', 'NV_KD01'),
('DH183', 'Đơn hàng bổ sung 183', 130000000, '2024-04-27', 'Đang sản xuất', 'contact183@example.com', 'KH001', 'NV_KD01'),
('DH184', 'Đơn hàng bổ sung 184', 135000000, '2024-04-28', 'Đang sản xuất', 'contact184@example.com', 'KH002', 'NV_KD01'),
('DH185', 'Đơn hàng bổ sung 185', 140000000, '2024-04-01', 'Đã xác nhận', 'contact185@example.com', 'KH001', 'NV_KD01'),
('DH186', 'Đơn hàng bổ sung 186', 145000000, '2024-04-02', 'Đang sản xuất', 'contact186@example.com', 'KH002', 'NV_KD01'),
('DH187', 'Đơn hàng bổ sung 187', 150000000, '2024-04-03', 'Đang sản xuất', 'contact187@example.com', 'KH001', 'NV_KD01'),
('DH188', 'Đơn hàng bổ sung 188', 155000000, '2024-04-04', 'Đã xác nhận', 'contact188@example.com', 'KH002', 'NV_KD01'),
('DH189', 'Đơn hàng bổ sung 189', 160000000, '2024-04-05', 'Đang sản xuất', 'contact189@example.com', 'KH001', 'NV_KD01'),
('DH190', 'Đơn hàng bổ sung 190', 165000000, '2024-04-06', 'Đang sản xuất', 'contact190@example.com', 'KH002', 'NV_KD01'),
('DH191', 'Đơn hàng bổ sung 191', 120000000, '2024-04-07', 'Đã xác nhận', 'contact191@example.com', 'KH001', 'NV_KD01'),
('DH192', 'Đơn hàng bổ sung 192', 125000000, '2024-04-08', 'Đang sản xuất', 'contact192@example.com', 'KH002', 'NV_KD01'),
('DH193', 'Đơn hàng bổ sung 193', 130000000, '2024-04-09', 'Đang sản xuất', 'contact193@example.com', 'KH001', 'NV_KD01'),
('DH194', 'Đơn hàng bổ sung 194', 135000000, '2024-04-10', 'Đã xác nhận', 'contact194@example.com', 'KH002', 'NV_KD01'),
('DH195', 'Đơn hàng bổ sung 195', 140000000, '2024-04-11', 'Đang sản xuất', 'contact195@example.com', 'KH001', 'NV_KD01'),
('DH196', 'Đơn hàng bổ sung 196', 145000000, '2024-04-12', 'Đang sản xuất', 'contact196@example.com', 'KH002', 'NV_KD01'),
('DH197', 'Đơn hàng bổ sung 197', 150000000, '2024-04-13', 'Đã xác nhận', 'contact197@example.com', 'KH001', 'NV_KD01'),
('DH198', 'Đơn hàng bổ sung 198', 155000000, '2024-04-14', 'Đang sản xuất', 'contact198@example.com', 'KH002', 'NV_KD01'),
('DH199', 'Đơn hàng bổ sung 199', 160000000, '2024-04-15', 'Đang sản xuất', 'contact199@example.com', 'KH001', 'NV_KD01'),
('DH200', 'Đơn hàng bổ sung 200', 165000000, '2024-04-16', 'Đã xác nhận', 'contact200@example.com', 'KH002', 'NV_KD01'),
('DH201', 'Đơn hàng bổ sung 201', 120000000, '2024-04-17', 'Đang sản xuất', 'contact201@example.com', 'KH001', 'NV_KD01'),
('DH202', 'Đơn hàng bổ sung 202', 125000000, '2024-04-18', 'Đang sản xuất', 'contact202@example.com', 'KH002', 'NV_KD01'),
('DH203', 'Đơn hàng bổ sung 203', 130000000, '2024-04-19', 'Đã xác nhận', 'contact203@example.com', 'KH001', 'NV_KD01'),
('DH204', 'Đơn hàng bổ sung 204', 135000000, '2024-04-20', 'Đang sản xuất', 'contact204@example.com', 'KH002', 'NV_KD01'),
('DH205', 'Đơn hàng bổ sung 205', 140000000, '2024-04-21', 'Đang sản xuất', 'contact205@example.com', 'KH001', 'NV_KD01'),
('DH206', 'Đơn hàng bổ sung 206', 145000000, '2024-04-22', 'Đã xác nhận', 'contact206@example.com', 'KH002', 'NV_KD01'),
('DH207', 'Đơn hàng bổ sung 207', 150000000, '2024-04-23', 'Đang sản xuất', 'contact207@example.com', 'KH001', 'NV_KD01'),
('DH208', 'Đơn hàng bổ sung 208', 155000000, '2024-04-24', 'Đang sản xuất', 'contact208@example.com', 'KH002', 'NV_KD01'),
('DH209', 'Đơn hàng bổ sung 209', 160000000, '2024-04-25', 'Đã xác nhận', 'contact209@example.com', 'KH001', 'NV_KD01'),
('DH210', 'Đơn hàng bổ sung 210', 165000000, '2024-04-26', 'Đang sản xuất', 'contact210@example.com', 'KH002', 'NV_KD01'),
('DH211', 'Đơn hàng bổ sung 211', 120000000, '2024-04-27', 'Đang sản xuất', 'contact211@example.com', 'KH001', 'NV_KD01'),
('DH212', 'Đơn hàng bổ sung 212', 125000000, '2024-04-28', 'Đã xác nhận', 'contact212@example.com', 'KH002', 'NV_KD01'),
('DH213', 'Đơn hàng bổ sung 213', 130000000, '2024-04-01', 'Đang sản xuất', 'contact213@example.com', 'KH001', 'NV_KD01'),
('DH214', 'Đơn hàng bổ sung 214', 135000000, '2024-04-02', 'Đang sản xuất', 'contact214@example.com', 'KH002', 'NV_KD01'),
('DH215', 'Đơn hàng bổ sung 215', 140000000, '2024-04-03', 'Đã xác nhận', 'contact215@example.com', 'KH001', 'NV_KD01'),
('DH216', 'Đơn hàng bổ sung 216', 145000000, '2024-04-04', 'Đang sản xuất', 'contact216@example.com', 'KH002', 'NV_KD01'),
('DH217', 'Đơn hàng bổ sung 217', 150000000, '2024-04-05', 'Đang sản xuất', 'contact217@example.com', 'KH001', 'NV_KD01'),
('DH218', 'Đơn hàng bổ sung 218', 155000000, '2024-04-06', 'Đã xác nhận', 'contact218@example.com', 'KH002', 'NV_KD01'),
('DH219', 'Đơn hàng bổ sung 219', 160000000, '2024-04-07', 'Đang sản xuất', 'contact219@example.com', 'KH001', 'NV_KD01'),
('DH220', 'Đơn hàng bổ sung 220', 165000000, '2024-04-08', 'Đang sản xuất', 'contact220@example.com', 'KH002', 'NV_KD01');

INSERT INTO `ct_don_hang` (`IdTTCTDonHang`, `SoLuong`, `NgayGiao`, `YeuCau`, `DonGia`, `ThanhTien`, `GhiChu`, `VAT`, `IdSanPham`, `IdCauHinh`, `IdDonHang`) VALUES
('CTDH001', 100, '2024-04-25 17:00:00', 'Keycap PBT, switch đỏ', 2500000, 250000000, 'Đóng gói thương hiệu TechNova', 10, 'SP001', 'CH001', 'DH001'),
('CTDH002', 80, '2024-04-20 17:00:00', 'Layout 65%', 1900000, 152000000, 'Giao đợt 1', 10, 'SP002', 'CH002', 'DH002'),
('CTDH101', 50, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH101'),
('CTDH102', 51, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH102'),
('CTDH103', 52, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH103'),
('CTDH104', 53, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH104'),
('CTDH105', 54, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH105'),
('CTDH106', 55, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH106'),
('CTDH107', 56, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH107'),
('CTDH108', 57, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH108'),
('CTDH109', 58, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH109'),
('CTDH110', 59, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH110'),
('CTDH111', 60, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH111'),
('CTDH112', 61, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH112'),
('CTDH113', 62, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH113'),
('CTDH114', 63, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH114'),
('CTDH115', 64, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH115'),
('CTDH116', 65, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH116'),
('CTDH117', 66, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH117'),
('CTDH118', 67, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH118'),
('CTDH119', 68, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH119'),
('CTDH120', 69, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH120'),
('CTDH121', 50, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH121'),
('CTDH122', 51, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH122'),
('CTDH123', 52, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH123'),
('CTDH124', 53, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH124'),
('CTDH125', 54, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH125'),
('CTDH126', 55, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH126'),
('CTDH127', 56, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH127'),
('CTDH128', 57, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH128'),
('CTDH129', 58, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH129'),
('CTDH130', 59, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH130'),
('CTDH131', 60, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH131'),
('CTDH132', 61, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH132'),
('CTDH133', 62, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH133'),
('CTDH134', 63, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH134'),
('CTDH135', 64, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH135'),
('CTDH136', 65, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH136'),
('CTDH137', 66, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH137'),
('CTDH138', 67, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH138'),
('CTDH139', 68, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH139'),
('CTDH140', 69, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH140'),
('CTDH141', 50, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH141'),
('CTDH142', 51, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH142'),
('CTDH143', 52, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH143'),
('CTDH144', 53, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH144'),
('CTDH145', 54, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH145'),
('CTDH146', 55, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH146'),
('CTDH147', 56, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH147'),
('CTDH148', 57, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH148'),
('CTDH149', 58, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH149'),
('CTDH150', 59, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH150'),
('CTDH151', 60, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH151'),
('CTDH152', 61, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH152'),
('CTDH153', 62, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH153'),
('CTDH154', 63, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH154'),
('CTDH155', 64, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH155'),
('CTDH156', 65, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH156'),
('CTDH157', 66, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH157'),
('CTDH158', 67, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH158'),
('CTDH159', 68, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH159'),
('CTDH160', 69, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH160'),
('CTDH161', 50, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH161'),
('CTDH162', 51, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH162'),
('CTDH163', 52, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH163'),
('CTDH164', 53, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH164'),
('CTDH165', 54, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH165'),
('CTDH166', 55, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH166'),
('CTDH167', 56, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH167'),
('CTDH168', 57, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH168'),
('CTDH169', 58, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH169'),
('CTDH170', 59, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH170'),
('CTDH171', 60, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH171'),
('CTDH172', 61, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH172'),
('CTDH173', 62, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH173'),
('CTDH174', 63, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH174'),
('CTDH175', 64, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH175'),
('CTDH176', 65, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH176'),
('CTDH177', 66, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH177'),
('CTDH178', 67, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH178'),
('CTDH179', 68, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH179'),
('CTDH180', 69, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH180'),
('CTDH181', 50, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH181'),
('CTDH182', 51, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH182'),
('CTDH183', 52, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH183'),
('CTDH184', 53, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH184'),
('CTDH185', 54, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH185'),
('CTDH186', 55, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH186'),
('CTDH187', 56, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH187'),
('CTDH188', 57, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH188'),
('CTDH189', 58, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH189'),
('CTDH190', 59, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH190'),
('CTDH191', 60, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH191'),
('CTDH192', 61, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH192'),
('CTDH193', 62, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH193'),
('CTDH194', 63, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH194'),
('CTDH195', 64, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH195'),
('CTDH196', 65, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH196'),
('CTDH197', 66, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH197'),
('CTDH198', 67, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH198'),
('CTDH199', 68, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH199'),
('CTDH200', 69, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH200'),
('CTDH201', 50, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 60000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH201'),
('CTDH202', 51, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 71400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH202'),
('CTDH203', 52, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 83200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH203'),
('CTDH204', 53, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 95400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH204'),
('CTDH205', 54, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 108000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH205'),
('CTDH206', 55, '2024-04-21 17:00:00', 'Yêu cầu bổ sung', 1200000, 66000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH206'),
('CTDH207', 56, '2024-04-22 17:00:00', 'Yêu cầu bổ sung', 1400000, 78400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH207'),
('CTDH208', 57, '2024-04-23 17:00:00', 'Yêu cầu bổ sung', 1600000, 91200000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH208'),
('CTDH209', 58, '2024-04-24 17:00:00', 'Yêu cầu bổ sung', 1800000, 104400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH209'),
('CTDH210', 59, '2024-04-25 17:00:00', 'Yêu cầu bổ sung', 2000000, 118000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH210'),
('CTDH211', 60, '2024-04-26 17:00:00', 'Yêu cầu bổ sung', 1200000, 72000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH211'),
('CTDH212', 61, '2024-04-27 17:00:00', 'Yêu cầu bổ sung', 1400000, 85400000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH212'),
('CTDH213', 62, '2024-04-28 17:00:00', 'Yêu cầu bổ sung', 1600000, 99200000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH213'),
('CTDH214', 63, '2024-04-29 17:00:00', 'Yêu cầu bổ sung', 1800000, 113400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH214'),
('CTDH215', 64, '2024-04-30 17:00:00', 'Yêu cầu bổ sung', 2000000, 128000000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH215'),
('CTDH216', 65, '2024-05-01 17:00:00', 'Yêu cầu bổ sung', 1200000, 78000000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH216'),
('CTDH217', 66, '2024-05-02 17:00:00', 'Yêu cầu bổ sung', 1400000, 92400000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH217'),
('CTDH218', 67, '2024-05-03 17:00:00', 'Yêu cầu bổ sung', 1600000, 107200000, 'Giao theo đợt', 10, 'SP001', 'CH001', 'DH218'),
('CTDH219', 68, '2024-05-04 17:00:00', 'Yêu cầu bổ sung', 1800000, 122400000, 'Giao theo đợt', 10, 'SP002', 'CH002', 'DH219'),
('CTDH220', 69, '2024-05-05 17:00:00', 'Yêu cầu bổ sung', 2000000, 138000000, 'Giao theo đợt', 10, 'SP003', 'CH003', 'DH220');

INSERT INTO `ke_hoach_san_xuat` (`IdKeHoachSanXuat`, `SoLuong`, `ThoiGianKetThuc`, `TrangThai`, `ThoiGianBD`, `IdNguoiLap`, `IdTTCTDonHang`) VALUES
('KHSX001', 100, '2024-04-22 17:00:00', 'Đang thực hiện', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH001'),
('KHSX002', 80, '2024-04-20 17:00:00', 'Lên kế hoạch', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH002'),
('KHSX101', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH101'),
('KHSX102', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH102'),
('KHSX103', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH103'),
('KHSX104', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH104'),
('KHSX105', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH105'),
('KHSX106', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH106'),
('KHSX107', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH107'),
('KHSX108', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH108'),
('KHSX109', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH109'),
('KHSX110', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH110'),
('KHSX111', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH111'),
('KHSX112', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH112'),
('KHSX113', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH113'),
('KHSX114', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH114'),
('KHSX115', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH115'),
('KHSX116', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH116'),
('KHSX117', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH117'),
('KHSX118', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH118'),
('KHSX119', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH119'),
('KHSX120', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH120'),
('KHSX121', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH121'),
('KHSX122', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH122'),
('KHSX123', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH123'),
('KHSX124', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH124'),
('KHSX125', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH125'),
('KHSX126', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH126'),
('KHSX127', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH127'),
('KHSX128', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH128'),
('KHSX129', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH129'),
('KHSX130', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH130'),
('KHSX131', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH131'),
('KHSX132', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH132'),
('KHSX133', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH133'),
('KHSX134', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH134'),
('KHSX135', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH135'),
('KHSX136', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH136'),
('KHSX137', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH137'),
('KHSX138', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH138'),
('KHSX139', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH139'),
('KHSX140', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH140'),
('KHSX141', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH141'),
('KHSX142', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH142'),
('KHSX143', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH143'),
('KHSX144', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH144'),
('KHSX145', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH145'),
('KHSX146', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH146'),
('KHSX147', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH147'),
('KHSX148', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH148'),
('KHSX149', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH149'),
('KHSX150', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH150'),
('KHSX151', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH151'),
('KHSX152', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH152'),
('KHSX153', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH153'),
('KHSX154', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH154'),
('KHSX155', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH155'),
('KHSX156', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH156'),
('KHSX157', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH157'),
('KHSX158', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH158'),
('KHSX159', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH159'),
('KHSX160', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH160'),
('KHSX161', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH161'),
('KHSX162', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH162'),
('KHSX163', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH163'),
('KHSX164', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH164'),
('KHSX165', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH165'),
('KHSX166', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH166'),
('KHSX167', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH167'),
('KHSX168', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH168'),
('KHSX169', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH169'),
('KHSX170', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH170'),
('KHSX171', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH171'),
('KHSX172', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH172'),
('KHSX173', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH173'),
('KHSX174', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH174'),
('KHSX175', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH175'),
('KHSX176', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH176'),
('KHSX177', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH177'),
('KHSX178', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH178'),
('KHSX179', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH179'),
('KHSX180', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH180'),
('KHSX181', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH181'),
('KHSX182', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH182'),
('KHSX183', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH183'),
('KHSX184', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH184'),
('KHSX185', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH185'),
('KHSX186', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH186'),
('KHSX187', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH187'),
('KHSX188', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH188'),
('KHSX189', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH189'),
('KHSX190', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH190'),
('KHSX191', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH191'),
('KHSX192', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH192'),
('KHSX193', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH193'),
('KHSX194', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH194'),
('KHSX195', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH195'),
('KHSX196', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH196'),
('KHSX197', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH197'),
('KHSX198', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH198'),
('KHSX199', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH199'),
('KHSX200', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH200'),
('KHSX201', 50, '2024-04-11 17:00:00', 'Lên kế hoạch', '2024-04-01 08:00:00', 'NV_XU01', 'CTDH201'),
('KHSX202', 51, '2024-04-13 17:00:00', 'Đang thực hiện', '2024-04-02 08:00:00', 'NV_XU02', 'CTDH202'),
('KHSX203', 52, '2024-04-15 17:00:00', 'Đang thực hiện', '2024-04-03 08:00:00', 'NV_XU01', 'CTDH203'),
('KHSX204', 53, '2024-04-17 17:00:00', 'Đang thực hiện', '2024-04-04 08:00:00', 'NV_XU02', 'CTDH204'),
('KHSX205', 54, '2024-04-19 17:00:00', 'Lên kế hoạch', '2024-04-05 08:00:00', 'NV_XU01', 'CTDH205'),
('KHSX206', 55, '2024-04-16 17:00:00', 'Đang thực hiện', '2024-04-06 08:00:00', 'NV_XU02', 'CTDH206'),
('KHSX207', 56, '2024-04-18 17:00:00', 'Đang thực hiện', '2024-04-07 08:00:00', 'NV_XU01', 'CTDH207'),
('KHSX208', 57, '2024-04-20 17:00:00', 'Đang thực hiện', '2024-04-08 08:00:00', 'NV_XU02', 'CTDH208'),
('KHSX209', 58, '2024-04-22 17:00:00', 'Lên kế hoạch', '2024-04-09 08:00:00', 'NV_XU01', 'CTDH209'),
('KHSX210', 59, '2024-04-24 17:00:00', 'Đang thực hiện', '2024-04-10 08:00:00', 'NV_XU02', 'CTDH210'),
('KHSX211', 60, '2024-04-21 17:00:00', 'Đang thực hiện', '2024-04-11 08:00:00', 'NV_XU01', 'CTDH211'),
('KHSX212', 61, '2024-04-23 17:00:00', 'Đang thực hiện', '2024-04-12 08:00:00', 'NV_XU02', 'CTDH212'),
('KHSX213', 62, '2024-04-25 17:00:00', 'Lên kế hoạch', '2024-04-13 08:00:00', 'NV_XU01', 'CTDH213'),
('KHSX214', 63, '2024-04-27 17:00:00', 'Đang thực hiện', '2024-04-14 08:00:00', 'NV_XU02', 'CTDH214'),
('KHSX215', 64, '2024-04-29 17:00:00', 'Đang thực hiện', '2024-04-15 08:00:00', 'NV_XU01', 'CTDH215'),
('KHSX216', 65, '2024-04-26 17:00:00', 'Đang thực hiện', '2024-04-16 08:00:00', 'NV_XU02', 'CTDH216'),
('KHSX217', 66, '2024-04-28 17:00:00', 'Lên kế hoạch', '2024-04-17 08:00:00', 'NV_XU01', 'CTDH217'),
('KHSX218', 67, '2024-04-30 17:00:00', 'Đang thực hiện', '2024-04-18 08:00:00', 'NV_XU02', 'CTDH218'),
('KHSX219', 68, '2024-05-02 17:00:00', 'Đang thực hiện', '2024-04-19 08:00:00', 'NV_XU01', 'CTDH219'),
('KHSX220', 69, '2024-05-04 17:00:00', 'Đang thực hiện', '2024-04-20 08:00:00', 'NV_XU02', 'CTDH220');

INSERT INTO `xuong_cau_hinh_san_pham` (`IdPhanCong`, `IdSanPham`, `IdCauHinh`, `IdXuong`, `TenPhanCong`, `TyLeSoLuong`, `DonVi`, `TrangThaiMacDinh`, `LogisticsKey`, `LogisticsLabel`, `IncludeYeuCau`, `ThuTu`) VALUES
('PC001', 'SP001', 'CH001', 'XUONG01', 'Lắp ráp PCB & switch', 1, 'bộ', 'Đang chạy', 'assembly', 'Lắp ráp', 1, 1),
('PC002', 'SP001', 'CH001', 'XUONG02', 'Hoàn thiện & QC', 1, 'bộ', 'Chờ', 'qc', 'Kiểm tra chất lượng', 1, 2),
('PC003', 'SP002', 'CH002', 'XUONG01', 'Lắp ráp 65%', 1, 'bộ', 'Đang chạy', 'assembly65', 'Lắp ráp 65%', 1, 1);

INSERT INTO `ke_hoach_san_xuat_xuong` (`IdKeHoachSanXuatXuong`, `TenThanhThanhPhanSP`, `SoLuong`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TrangThai`, `TinhTrangVatTu`, `IdCongDoan`, `IdKeHoachSanXuat`, `IdXuong`) VALUES
('KHSXX001', 'MecaKey 75 Pro - Lắp ráp', 100, '2024-04-08 08:00:00', '2024-04-15 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC001', 'KHSX001', 'XUONG01'),
('KHSXX002', 'MecaKey 75 Pro - QC', 100, '2024-04-16 08:00:00', '2024-04-20 17:00:00', 'Chờ QC', 'Đã cấp phát', 'PC002', 'KHSX001', 'XUONG02'),
('KHSXX003', 'MecaKey 65 Lite - Lắp ráp', 80, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Lên kế hoạch', 'Chưa kiểm tra', 'PC003', 'KHSX002', 'XUONG01'),
('KHSXX101', 'Kế hoạch xưởng 101', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX101', 'XUONG01'),
('KHSXX102', 'Kế hoạch xưởng 102', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX102', 'XUONG02'),
('KHSXX103', 'Kế hoạch xưởng 103', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX103', 'XUONG01'),
('KHSXX104', 'Kế hoạch xưởng 104', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX104', 'XUONG02'),
('KHSXX105', 'Kế hoạch xưởng 105', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX105', 'XUONG01'),
('KHSXX106', 'Kế hoạch xưởng 106', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX106', 'XUONG02'),
('KHSXX107', 'Kế hoạch xưởng 107', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX107', 'XUONG01'),
('KHSXX108', 'Kế hoạch xưởng 108', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX108', 'XUONG02'),
('KHSXX109', 'Kế hoạch xưởng 109', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX109', 'XUONG01'),
('KHSXX110', 'Kế hoạch xưởng 110', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX110', 'XUONG02'),
('KHSXX111', 'Kế hoạch xưởng 111', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX111', 'XUONG01'),
('KHSXX112', 'Kế hoạch xưởng 112', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX112', 'XUONG02'),
('KHSXX113', 'Kế hoạch xưởng 113', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX113', 'XUONG01'),
('KHSXX114', 'Kế hoạch xưởng 114', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX114', 'XUONG02'),
('KHSXX115', 'Kế hoạch xưởng 115', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX115', 'XUONG01'),
('KHSXX116', 'Kế hoạch xưởng 116', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX116', 'XUONG02'),
('KHSXX117', 'Kế hoạch xưởng 117', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX117', 'XUONG01'),
('KHSXX118', 'Kế hoạch xưởng 118', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX118', 'XUONG02'),
('KHSXX119', 'Kế hoạch xưởng 119', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX119', 'XUONG01'),
('KHSXX120', 'Kế hoạch xưởng 120', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX120', 'XUONG02'),
('KHSXX121', 'Kế hoạch xưởng 121', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX121', 'XUONG01'),
('KHSXX122', 'Kế hoạch xưởng 122', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX122', 'XUONG02'),
('KHSXX123', 'Kế hoạch xưởng 123', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX123', 'XUONG01'),
('KHSXX124', 'Kế hoạch xưởng 124', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX124', 'XUONG02'),
('KHSXX125', 'Kế hoạch xưởng 125', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX125', 'XUONG01'),
('KHSXX126', 'Kế hoạch xưởng 126', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX126', 'XUONG02'),
('KHSXX127', 'Kế hoạch xưởng 127', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX127', 'XUONG01'),
('KHSXX128', 'Kế hoạch xưởng 128', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX128', 'XUONG02'),
('KHSXX129', 'Kế hoạch xưởng 129', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX129', 'XUONG01'),
('KHSXX130', 'Kế hoạch xưởng 130', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX130', 'XUONG02'),
('KHSXX131', 'Kế hoạch xưởng 131', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX131', 'XUONG01'),
('KHSXX132', 'Kế hoạch xưởng 132', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX132', 'XUONG02'),
('KHSXX133', 'Kế hoạch xưởng 133', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX133', 'XUONG01'),
('KHSXX134', 'Kế hoạch xưởng 134', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX134', 'XUONG02'),
('KHSXX135', 'Kế hoạch xưởng 135', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX135', 'XUONG01'),
('KHSXX136', 'Kế hoạch xưởng 136', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX136', 'XUONG02'),
('KHSXX137', 'Kế hoạch xưởng 137', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX137', 'XUONG01'),
('KHSXX138', 'Kế hoạch xưởng 138', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX138', 'XUONG02'),
('KHSXX139', 'Kế hoạch xưởng 139', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX139', 'XUONG01'),
('KHSXX140', 'Kế hoạch xưởng 140', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX140', 'XUONG02'),
('KHSXX141', 'Kế hoạch xưởng 141', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX141', 'XUONG01'),
('KHSXX142', 'Kế hoạch xưởng 142', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX142', 'XUONG02'),
('KHSXX143', 'Kế hoạch xưởng 143', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX143', 'XUONG01'),
('KHSXX144', 'Kế hoạch xưởng 144', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX144', 'XUONG02'),
('KHSXX145', 'Kế hoạch xưởng 145', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX145', 'XUONG01'),
('KHSXX146', 'Kế hoạch xưởng 146', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX146', 'XUONG02'),
('KHSXX147', 'Kế hoạch xưởng 147', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX147', 'XUONG01'),
('KHSXX148', 'Kế hoạch xưởng 148', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX148', 'XUONG02'),
('KHSXX149', 'Kế hoạch xưởng 149', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX149', 'XUONG01'),
('KHSXX150', 'Kế hoạch xưởng 150', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX150', 'XUONG02'),
('KHSXX151', 'Kế hoạch xưởng 151', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX151', 'XUONG01'),
('KHSXX152', 'Kế hoạch xưởng 152', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX152', 'XUONG02'),
('KHSXX153', 'Kế hoạch xưởng 153', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX153', 'XUONG01'),
('KHSXX154', 'Kế hoạch xưởng 154', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX154', 'XUONG02'),
('KHSXX155', 'Kế hoạch xưởng 155', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX155', 'XUONG01'),
('KHSXX156', 'Kế hoạch xưởng 156', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX156', 'XUONG02'),
('KHSXX157', 'Kế hoạch xưởng 157', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX157', 'XUONG01'),
('KHSXX158', 'Kế hoạch xưởng 158', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX158', 'XUONG02'),
('KHSXX159', 'Kế hoạch xưởng 159', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX159', 'XUONG01'),
('KHSXX160', 'Kế hoạch xưởng 160', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX160', 'XUONG02'),
('KHSXX161', 'Kế hoạch xưởng 161', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX161', 'XUONG01'),
('KHSXX162', 'Kế hoạch xưởng 162', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX162', 'XUONG02'),
('KHSXX163', 'Kế hoạch xưởng 163', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX163', 'XUONG01'),
('KHSXX164', 'Kế hoạch xưởng 164', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX164', 'XUONG02'),
('KHSXX165', 'Kế hoạch xưởng 165', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX165', 'XUONG01'),
('KHSXX166', 'Kế hoạch xưởng 166', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX166', 'XUONG02'),
('KHSXX167', 'Kế hoạch xưởng 167', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX167', 'XUONG01'),
('KHSXX168', 'Kế hoạch xưởng 168', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX168', 'XUONG02'),
('KHSXX169', 'Kế hoạch xưởng 169', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX169', 'XUONG01'),
('KHSXX170', 'Kế hoạch xưởng 170', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX170', 'XUONG02'),
('KHSXX171', 'Kế hoạch xưởng 171', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX171', 'XUONG01'),
('KHSXX172', 'Kế hoạch xưởng 172', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX172', 'XUONG02'),
('KHSXX173', 'Kế hoạch xưởng 173', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX173', 'XUONG01'),
('KHSXX174', 'Kế hoạch xưởng 174', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX174', 'XUONG02'),
('KHSXX175', 'Kế hoạch xưởng 175', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX175', 'XUONG01'),
('KHSXX176', 'Kế hoạch xưởng 176', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX176', 'XUONG02'),
('KHSXX177', 'Kế hoạch xưởng 177', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX177', 'XUONG01'),
('KHSXX178', 'Kế hoạch xưởng 178', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX178', 'XUONG02'),
('KHSXX179', 'Kế hoạch xưởng 179', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX179', 'XUONG01'),
('KHSXX180', 'Kế hoạch xưởng 180', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX180', 'XUONG02'),
('KHSXX181', 'Kế hoạch xưởng 181', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX181', 'XUONG01'),
('KHSXX182', 'Kế hoạch xưởng 182', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX182', 'XUONG02'),
('KHSXX183', 'Kế hoạch xưởng 183', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX183', 'XUONG01'),
('KHSXX184', 'Kế hoạch xưởng 184', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX184', 'XUONG02'),
('KHSXX185', 'Kế hoạch xưởng 185', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX185', 'XUONG01'),
('KHSXX186', 'Kế hoạch xưởng 186', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX186', 'XUONG02'),
('KHSXX187', 'Kế hoạch xưởng 187', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX187', 'XUONG01'),
('KHSXX188', 'Kế hoạch xưởng 188', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX188', 'XUONG02'),
('KHSXX189', 'Kế hoạch xưởng 189', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX189', 'XUONG01'),
('KHSXX190', 'Kế hoạch xưởng 190', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX190', 'XUONG02'),
('KHSXX191', 'Kế hoạch xưởng 191', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX191', 'XUONG01'),
('KHSXX192', 'Kế hoạch xưởng 192', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX192', 'XUONG02'),
('KHSXX193', 'Kế hoạch xưởng 193', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX193', 'XUONG01'),
('KHSXX194', 'Kế hoạch xưởng 194', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX194', 'XUONG02'),
('KHSXX195', 'Kế hoạch xưởng 195', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX195', 'XUONG01'),
('KHSXX196', 'Kế hoạch xưởng 196', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX196', 'XUONG02'),
('KHSXX197', 'Kế hoạch xưởng 197', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX197', 'XUONG01'),
('KHSXX198', 'Kế hoạch xưởng 198', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX198', 'XUONG02'),
('KHSXX199', 'Kế hoạch xưởng 199', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX199', 'XUONG01'),
('KHSXX200', 'Kế hoạch xưởng 200', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX200', 'XUONG02'),
('KHSXX201', 'Kế hoạch xưởng 201', 50, '2024-04-01 08:00:00', '2024-04-08 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX201', 'XUONG01'),
('KHSXX202', 'Kế hoạch xưởng 202', 51, '2024-04-02 08:00:00', '2024-04-10 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX202', 'XUONG02'),
('KHSXX203', 'Kế hoạch xưởng 203', 52, '2024-04-03 08:00:00', '2024-04-12 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX203', 'XUONG01'),
('KHSXX204', 'Kế hoạch xưởng 204', 53, '2024-04-04 08:00:00', '2024-04-14 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX204', 'XUONG02'),
('KHSXX205', 'Kế hoạch xưởng 205', 54, '2024-04-05 08:00:00', '2024-04-12 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX205', 'XUONG01'),
('KHSXX206', 'Kế hoạch xưởng 206', 55, '2024-04-06 08:00:00', '2024-04-14 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX206', 'XUONG02'),
('KHSXX207', 'Kế hoạch xưởng 207', 56, '2024-04-07 08:00:00', '2024-04-16 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX207', 'XUONG01'),
('KHSXX208', 'Kế hoạch xưởng 208', 57, '2024-04-08 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX208', 'XUONG02'),
('KHSXX209', 'Kế hoạch xưởng 209', 58, '2024-04-09 08:00:00', '2024-04-16 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX209', 'XUONG01'),
('KHSXX210', 'Kế hoạch xưởng 210', 59, '2024-04-10 08:00:00', '2024-04-18 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX210', 'XUONG02'),
('KHSXX211', 'Kế hoạch xưởng 211', 60, '2024-04-11 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX211', 'XUONG01'),
('KHSXX212', 'Kế hoạch xưởng 212', 61, '2024-04-12 08:00:00', '2024-04-22 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX212', 'XUONG02'),
('KHSXX213', 'Kế hoạch xưởng 213', 62, '2024-04-13 08:00:00', '2024-04-20 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX213', 'XUONG01'),
('KHSXX214', 'Kế hoạch xưởng 214', 63, '2024-04-14 08:00:00', '2024-04-22 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX214', 'XUONG02'),
('KHSXX215', 'Kế hoạch xưởng 215', 64, '2024-04-15 08:00:00', '2024-04-24 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX215', 'XUONG01'),
('KHSXX216', 'Kế hoạch xưởng 216', 65, '2024-04-16 08:00:00', '2024-04-26 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX216', 'XUONG02'),
('KHSXX217', 'Kế hoạch xưởng 217', 66, '2024-04-17 08:00:00', '2024-04-24 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX217', 'XUONG01'),
('KHSXX218', 'Kế hoạch xưởng 218', 67, '2024-04-18 08:00:00', '2024-04-26 17:00:00', 'Chờ QC', 'Chưa kiểm tra', 'PC001', 'KHSX218', 'XUONG02'),
('KHSXX219', 'Kế hoạch xưởng 219', 68, '2024-04-19 08:00:00', '2024-04-28 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC002', 'KHSX219', 'XUONG01'),
('KHSXX220', 'Kế hoạch xưởng 220', 69, '2024-04-20 08:00:00', '2024-04-30 17:00:00', 'Đang sản xuất', 'Đã cấp phát', 'PC003', 'KHSX220', 'XUONG02');

INSERT INTO `chi_tiet_ke_hoach_san_xuat_xuong` (`IdCTKHSXX`, `SoLuong`, `IdKeHoachSanXuatXuong`, `IdNguyenLieu`) VALUES
('CTKHSXX001', 120, 'KHSXX001', 'NL001'),
('CTKHSXX002', 850, 'KHSXX001', 'NL002'),
('CTKHSXX003', 100, 'KHSXX001', 'NL003'),
('CTKHSXX004', 100, 'KHSXX001', 'NL004'),
('CTKHSXX005', 100, 'KHSXX001', 'NL005'),
('CTKHSXX101', 60, 'KHSXX101', 'NL001'),
('CTKHSXX102', 61, 'KHSXX102', 'NL002'),
('CTKHSXX103', 62, 'KHSXX103', 'NL003'),
('CTKHSXX104', 63, 'KHSXX104', 'NL004'),
('CTKHSXX105', 64, 'KHSXX105', 'NL005'),
('CTKHSXX106', 65, 'KHSXX106', 'NL001'),
('CTKHSXX107', 66, 'KHSXX107', 'NL002'),
('CTKHSXX108', 67, 'KHSXX108', 'NL003'),
('CTKHSXX109', 68, 'KHSXX109', 'NL004'),
('CTKHSXX110', 69, 'KHSXX110', 'NL005'),
('CTKHSXX111', 70, 'KHSXX111', 'NL001'),
('CTKHSXX112', 71, 'KHSXX112', 'NL002'),
('CTKHSXX113', 72, 'KHSXX113', 'NL003'),
('CTKHSXX114', 73, 'KHSXX114', 'NL004'),
('CTKHSXX115', 74, 'KHSXX115', 'NL005'),
('CTKHSXX116', 75, 'KHSXX116', 'NL001'),
('CTKHSXX117', 76, 'KHSXX117', 'NL002'),
('CTKHSXX118', 77, 'KHSXX118', 'NL003'),
('CTKHSXX119', 78, 'KHSXX119', 'NL004'),
('CTKHSXX120', 79, 'KHSXX120', 'NL005'),
('CTKHSXX121', 80, 'KHSXX121', 'NL001'),
('CTKHSXX122', 81, 'KHSXX122', 'NL002'),
('CTKHSXX123', 82, 'KHSXX123', 'NL003'),
('CTKHSXX124', 83, 'KHSXX124', 'NL004'),
('CTKHSXX125', 84, 'KHSXX125', 'NL005'),
('CTKHSXX126', 85, 'KHSXX126', 'NL001'),
('CTKHSXX127', 86, 'KHSXX127', 'NL002'),
('CTKHSXX128', 87, 'KHSXX128', 'NL003'),
('CTKHSXX129', 88, 'KHSXX129', 'NL004'),
('CTKHSXX130', 89, 'KHSXX130', 'NL005'),
('CTKHSXX131', 90, 'KHSXX131', 'NL001'),
('CTKHSXX132', 91, 'KHSXX132', 'NL002'),
('CTKHSXX133', 92, 'KHSXX133', 'NL003'),
('CTKHSXX134', 93, 'KHSXX134', 'NL004'),
('CTKHSXX135', 94, 'KHSXX135', 'NL005'),
('CTKHSXX136', 95, 'KHSXX136', 'NL001'),
('CTKHSXX137', 96, 'KHSXX137', 'NL002'),
('CTKHSXX138', 97, 'KHSXX138', 'NL003'),
('CTKHSXX139', 98, 'KHSXX139', 'NL004'),
('CTKHSXX140', 99, 'KHSXX140', 'NL005'),
('CTKHSXX141', 60, 'KHSXX141', 'NL001'),
('CTKHSXX142', 61, 'KHSXX142', 'NL002'),
('CTKHSXX143', 62, 'KHSXX143', 'NL003'),
('CTKHSXX144', 63, 'KHSXX144', 'NL004'),
('CTKHSXX145', 64, 'KHSXX145', 'NL005'),
('CTKHSXX146', 65, 'KHSXX146', 'NL001'),
('CTKHSXX147', 66, 'KHSXX147', 'NL002'),
('CTKHSXX148', 67, 'KHSXX148', 'NL003'),
('CTKHSXX149', 68, 'KHSXX149', 'NL004'),
('CTKHSXX150', 69, 'KHSXX150', 'NL005'),
('CTKHSXX151', 70, 'KHSXX151', 'NL001'),
('CTKHSXX152', 71, 'KHSXX152', 'NL002'),
('CTKHSXX153', 72, 'KHSXX153', 'NL003'),
('CTKHSXX154', 73, 'KHSXX154', 'NL004'),
('CTKHSXX155', 74, 'KHSXX155', 'NL005'),
('CTKHSXX156', 75, 'KHSXX156', 'NL001'),
('CTKHSXX157', 76, 'KHSXX157', 'NL002'),
('CTKHSXX158', 77, 'KHSXX158', 'NL003'),
('CTKHSXX159', 78, 'KHSXX159', 'NL004'),
('CTKHSXX160', 79, 'KHSXX160', 'NL005'),
('CTKHSXX161', 80, 'KHSXX161', 'NL001'),
('CTKHSXX162', 81, 'KHSXX162', 'NL002'),
('CTKHSXX163', 82, 'KHSXX163', 'NL003'),
('CTKHSXX164', 83, 'KHSXX164', 'NL004'),
('CTKHSXX165', 84, 'KHSXX165', 'NL005'),
('CTKHSXX166', 85, 'KHSXX166', 'NL001'),
('CTKHSXX167', 86, 'KHSXX167', 'NL002'),
('CTKHSXX168', 87, 'KHSXX168', 'NL003'),
('CTKHSXX169', 88, 'KHSXX169', 'NL004'),
('CTKHSXX170', 89, 'KHSXX170', 'NL005'),
('CTKHSXX171', 90, 'KHSXX171', 'NL001'),
('CTKHSXX172', 91, 'KHSXX172', 'NL002'),
('CTKHSXX173', 92, 'KHSXX173', 'NL003'),
('CTKHSXX174', 93, 'KHSXX174', 'NL004'),
('CTKHSXX175', 94, 'KHSXX175', 'NL005'),
('CTKHSXX176', 95, 'KHSXX176', 'NL001'),
('CTKHSXX177', 96, 'KHSXX177', 'NL002'),
('CTKHSXX178', 97, 'KHSXX178', 'NL003'),
('CTKHSXX179', 98, 'KHSXX179', 'NL004'),
('CTKHSXX180', 99, 'KHSXX180', 'NL005'),
('CTKHSXX181', 60, 'KHSXX181', 'NL001'),
('CTKHSXX182', 61, 'KHSXX182', 'NL002'),
('CTKHSXX183', 62, 'KHSXX183', 'NL003'),
('CTKHSXX184', 63, 'KHSXX184', 'NL004'),
('CTKHSXX185', 64, 'KHSXX185', 'NL005'),
('CTKHSXX186', 65, 'KHSXX186', 'NL001'),
('CTKHSXX187', 66, 'KHSXX187', 'NL002'),
('CTKHSXX188', 67, 'KHSXX188', 'NL003'),
('CTKHSXX189', 68, 'KHSXX189', 'NL004'),
('CTKHSXX190', 69, 'KHSXX190', 'NL005'),
('CTKHSXX191', 70, 'KHSXX191', 'NL001'),
('CTKHSXX192', 71, 'KHSXX192', 'NL002'),
('CTKHSXX193', 72, 'KHSXX193', 'NL003'),
('CTKHSXX194', 73, 'KHSXX194', 'NL004'),
('CTKHSXX195', 74, 'KHSXX195', 'NL005'),
('CTKHSXX196', 75, 'KHSXX196', 'NL001'),
('CTKHSXX197', 76, 'KHSXX197', 'NL002'),
('CTKHSXX198', 77, 'KHSXX198', 'NL003'),
('CTKHSXX199', 78, 'KHSXX199', 'NL004'),
('CTKHSXX200', 79, 'KHSXX200', 'NL005'),
('CTKHSXX201', 80, 'KHSXX201', 'NL001'),
('CTKHSXX202', 81, 'KHSXX202', 'NL002'),
('CTKHSXX203', 82, 'KHSXX203', 'NL003'),
('CTKHSXX204', 83, 'KHSXX204', 'NL004'),
('CTKHSXX205', 84, 'KHSXX205', 'NL005'),
('CTKHSXX206', 85, 'KHSXX206', 'NL001'),
('CTKHSXX207', 86, 'KHSXX207', 'NL002'),
('CTKHSXX208', 87, 'KHSXX208', 'NL003'),
('CTKHSXX209', 88, 'KHSXX209', 'NL004'),
('CTKHSXX210', 89, 'KHSXX210', 'NL005'),
('CTKHSXX211', 90, 'KHSXX211', 'NL001'),
('CTKHSXX212', 91, 'KHSXX212', 'NL002'),
('CTKHSXX213', 92, 'KHSXX213', 'NL003'),
('CTKHSXX214', 93, 'KHSXX214', 'NL004'),
('CTKHSXX215', 94, 'KHSXX215', 'NL005'),
('CTKHSXX216', 95, 'KHSXX216', 'NL001'),
('CTKHSXX217', 96, 'KHSXX217', 'NL002'),
('CTKHSXX218', 97, 'KHSXX218', 'NL003'),
('CTKHSXX219', 98, 'KHSXX219', 'NL004'),
('CTKHSXX220', 99, 'KHSXX220', 'NL005');

INSERT INTO `yeu_cau_xuat_kho` (`IdYeuCau`, `IdKeHoachSanXuatXuong`, `NguoiYeuCau`, `TrangThai`, `NoiDung`, `NgayTao`) VALUES
('YCK001', 'KHSXX001', 'NV_XU01', 'Đã duyệt', 'Xuất nguyên liệu cho lắp ráp MecaKey 75 Pro', '2024-04-07 09:00:00'),
('YCK002', 'KHSXX003', 'NV_XU02', 'Chờ duyệt', 'Đề xuất xuất nguyên liệu cho MecaKey 65 Lite', '2024-04-08 09:00:00'),
('YCK101', 'KHSXX101', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 101', '2024-04-01 09:00:00'),
('YCK102', 'KHSXX102', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 102', '2024-04-02 09:00:00'),
('YCK103', 'KHSXX103', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 103', '2024-04-03 09:00:00'),
('YCK104', 'KHSXX104', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 104', '2024-04-04 09:00:00'),
('YCK105', 'KHSXX105', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 105', '2024-04-05 09:00:00'),
('YCK106', 'KHSXX106', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 106', '2024-04-06 09:00:00'),
('YCK107', 'KHSXX107', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 107', '2024-04-07 09:00:00'),
('YCK108', 'KHSXX108', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 108', '2024-04-08 09:00:00'),
('YCK109', 'KHSXX109', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 109', '2024-04-09 09:00:00'),
('YCK110', 'KHSXX110', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 110', '2024-04-10 09:00:00'),
('YCK111', 'KHSXX111', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 111', '2024-04-11 09:00:00'),
('YCK112', 'KHSXX112', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 112', '2024-04-12 09:00:00'),
('YCK113', 'KHSXX113', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 113', '2024-04-13 09:00:00'),
('YCK114', 'KHSXX114', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 114', '2024-04-14 09:00:00'),
('YCK115', 'KHSXX115', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 115', '2024-04-15 09:00:00'),
('YCK116', 'KHSXX116', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 116', '2024-04-16 09:00:00'),
('YCK117', 'KHSXX117', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 117', '2024-04-17 09:00:00'),
('YCK118', 'KHSXX118', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 118', '2024-04-18 09:00:00'),
('YCK119', 'KHSXX119', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 119', '2024-04-19 09:00:00'),
('YCK120', 'KHSXX120', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 120', '2024-04-20 09:00:00'),
('YCK121', 'KHSXX121', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 121', '2024-04-21 09:00:00'),
('YCK122', 'KHSXX122', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 122', '2024-04-22 09:00:00'),
('YCK123', 'KHSXX123', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 123', '2024-04-23 09:00:00'),
('YCK124', 'KHSXX124', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 124', '2024-04-24 09:00:00'),
('YCK125', 'KHSXX125', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 125', '2024-04-25 09:00:00'),
('YCK126', 'KHSXX126', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 126', '2024-04-01 09:00:00'),
('YCK127', 'KHSXX127', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 127', '2024-04-02 09:00:00'),
('YCK128', 'KHSXX128', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 128', '2024-04-03 09:00:00'),
('YCK129', 'KHSXX129', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 129', '2024-04-04 09:00:00'),
('YCK130', 'KHSXX130', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 130', '2024-04-05 09:00:00'),
('YCK131', 'KHSXX131', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 131', '2024-04-06 09:00:00'),
('YCK132', 'KHSXX132', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 132', '2024-04-07 09:00:00'),
('YCK133', 'KHSXX133', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 133', '2024-04-08 09:00:00'),
('YCK134', 'KHSXX134', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 134', '2024-04-09 09:00:00'),
('YCK135', 'KHSXX135', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 135', '2024-04-10 09:00:00'),
('YCK136', 'KHSXX136', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 136', '2024-04-11 09:00:00'),
('YCK137', 'KHSXX137', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 137', '2024-04-12 09:00:00'),
('YCK138', 'KHSXX138', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 138', '2024-04-13 09:00:00'),
('YCK139', 'KHSXX139', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 139', '2024-04-14 09:00:00'),
('YCK140', 'KHSXX140', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 140', '2024-04-15 09:00:00'),
('YCK141', 'KHSXX141', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 141', '2024-04-16 09:00:00'),
('YCK142', 'KHSXX142', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 142', '2024-04-17 09:00:00'),
('YCK143', 'KHSXX143', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 143', '2024-04-18 09:00:00'),
('YCK144', 'KHSXX144', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 144', '2024-04-19 09:00:00'),
('YCK145', 'KHSXX145', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 145', '2024-04-20 09:00:00'),
('YCK146', 'KHSXX146', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 146', '2024-04-21 09:00:00'),
('YCK147', 'KHSXX147', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 147', '2024-04-22 09:00:00'),
('YCK148', 'KHSXX148', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 148', '2024-04-23 09:00:00'),
('YCK149', 'KHSXX149', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 149', '2024-04-24 09:00:00'),
('YCK150', 'KHSXX150', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 150', '2024-04-25 09:00:00'),
('YCK151', 'KHSXX151', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 151', '2024-04-01 09:00:00'),
('YCK152', 'KHSXX152', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 152', '2024-04-02 09:00:00'),
('YCK153', 'KHSXX153', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 153', '2024-04-03 09:00:00'),
('YCK154', 'KHSXX154', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 154', '2024-04-04 09:00:00'),
('YCK155', 'KHSXX155', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 155', '2024-04-05 09:00:00'),
('YCK156', 'KHSXX156', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 156', '2024-04-06 09:00:00'),
('YCK157', 'KHSXX157', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 157', '2024-04-07 09:00:00'),
('YCK158', 'KHSXX158', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 158', '2024-04-08 09:00:00'),
('YCK159', 'KHSXX159', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 159', '2024-04-09 09:00:00'),
('YCK160', 'KHSXX160', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 160', '2024-04-10 09:00:00'),
('YCK161', 'KHSXX161', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 161', '2024-04-11 09:00:00'),
('YCK162', 'KHSXX162', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 162', '2024-04-12 09:00:00'),
('YCK163', 'KHSXX163', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 163', '2024-04-13 09:00:00'),
('YCK164', 'KHSXX164', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 164', '2024-04-14 09:00:00'),
('YCK165', 'KHSXX165', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 165', '2024-04-15 09:00:00'),
('YCK166', 'KHSXX166', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 166', '2024-04-16 09:00:00'),
('YCK167', 'KHSXX167', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 167', '2024-04-17 09:00:00'),
('YCK168', 'KHSXX168', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 168', '2024-04-18 09:00:00'),
('YCK169', 'KHSXX169', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 169', '2024-04-19 09:00:00'),
('YCK170', 'KHSXX170', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 170', '2024-04-20 09:00:00'),
('YCK171', 'KHSXX171', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 171', '2024-04-21 09:00:00'),
('YCK172', 'KHSXX172', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 172', '2024-04-22 09:00:00'),
('YCK173', 'KHSXX173', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 173', '2024-04-23 09:00:00'),
('YCK174', 'KHSXX174', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 174', '2024-04-24 09:00:00'),
('YCK175', 'KHSXX175', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 175', '2024-04-25 09:00:00'),
('YCK176', 'KHSXX176', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 176', '2024-04-01 09:00:00'),
('YCK177', 'KHSXX177', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 177', '2024-04-02 09:00:00'),
('YCK178', 'KHSXX178', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 178', '2024-04-03 09:00:00'),
('YCK179', 'KHSXX179', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 179', '2024-04-04 09:00:00'),
('YCK180', 'KHSXX180', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 180', '2024-04-05 09:00:00'),
('YCK181', 'KHSXX181', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 181', '2024-04-06 09:00:00'),
('YCK182', 'KHSXX182', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 182', '2024-04-07 09:00:00'),
('YCK183', 'KHSXX183', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 183', '2024-04-08 09:00:00'),
('YCK184', 'KHSXX184', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 184', '2024-04-09 09:00:00'),
('YCK185', 'KHSXX185', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 185', '2024-04-10 09:00:00'),
('YCK186', 'KHSXX186', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 186', '2024-04-11 09:00:00'),
('YCK187', 'KHSXX187', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 187', '2024-04-12 09:00:00'),
('YCK188', 'KHSXX188', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 188', '2024-04-13 09:00:00'),
('YCK189', 'KHSXX189', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 189', '2024-04-14 09:00:00'),
('YCK190', 'KHSXX190', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 190', '2024-04-15 09:00:00'),
('YCK191', 'KHSXX191', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 191', '2024-04-16 09:00:00'),
('YCK192', 'KHSXX192', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 192', '2024-04-17 09:00:00'),
('YCK193', 'KHSXX193', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 193', '2024-04-18 09:00:00'),
('YCK194', 'KHSXX194', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 194', '2024-04-19 09:00:00'),
('YCK195', 'KHSXX195', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 195', '2024-04-20 09:00:00'),
('YCK196', 'KHSXX196', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 196', '2024-04-21 09:00:00'),
('YCK197', 'KHSXX197', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 197', '2024-04-22 09:00:00'),
('YCK198', 'KHSXX198', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 198', '2024-04-23 09:00:00'),
('YCK199', 'KHSXX199', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 199', '2024-04-24 09:00:00'),
('YCK200', 'KHSXX200', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 200', '2024-04-25 09:00:00'),
('YCK201', 'KHSXX201', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 201', '2024-04-01 09:00:00'),
('YCK202', 'KHSXX202', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 202', '2024-04-02 09:00:00'),
('YCK203', 'KHSXX203', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 203', '2024-04-03 09:00:00'),
('YCK204', 'KHSXX204', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 204', '2024-04-04 09:00:00'),
('YCK205', 'KHSXX205', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 205', '2024-04-05 09:00:00'),
('YCK206', 'KHSXX206', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 206', '2024-04-06 09:00:00'),
('YCK207', 'KHSXX207', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 207', '2024-04-07 09:00:00'),
('YCK208', 'KHSXX208', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 208', '2024-04-08 09:00:00'),
('YCK209', 'KHSXX209', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 209', '2024-04-09 09:00:00'),
('YCK210', 'KHSXX210', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 210', '2024-04-10 09:00:00'),
('YCK211', 'KHSXX211', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 211', '2024-04-11 09:00:00'),
('YCK212', 'KHSXX212', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 212', '2024-04-12 09:00:00'),
('YCK213', 'KHSXX213', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 213', '2024-04-13 09:00:00'),
('YCK214', 'KHSXX214', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 214', '2024-04-14 09:00:00'),
('YCK215', 'KHSXX215', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 215', '2024-04-15 09:00:00'),
('YCK216', 'KHSXX216', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 216', '2024-04-16 09:00:00'),
('YCK217', 'KHSXX217', 'NV_XU01', 'Đã duyệt', 'Yêu cầu xuất kho bổ sung 217', '2024-04-17 09:00:00'),
('YCK218', 'KHSXX218', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 218', '2024-04-18 09:00:00'),
('YCK219', 'KHSXX219', 'NV_XU01', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 219', '2024-04-19 09:00:00'),
('YCK220', 'KHSXX220', 'NV_XU02', 'Chờ duyệt', 'Yêu cầu xuất kho bổ sung 220', '2024-04-20 09:00:00');

INSERT INTO `lich_su_ke_hoach_xuong` (`IdLichSu`, `IdKeHoachSanXuatXuong`, `TrangThai`, `HanhDong`, `GhiChu`, `NguoiThucHien`, `NgayThucHien`, `ThongTinChiTiet`, `IdYeuCauKho`) VALUES
('LSKH001', 'KHSXX001', 'Đang sản xuất', 'Khởi động lắp ráp', 'Đã cấp phát đủ vật tư', 'NV_XU01', '2024-04-08 08:15:00', '{\"so_ca\":2,\"tien_do\":\"30%\"}', 'YCK001'),
('LSKH002', 'KHSXX002', 'Chờ QC', 'Đang chờ hoàn thiện', 'Đang gom thành phẩm', 'NV_QC01', '2024-04-16 09:00:00', '{\"so_lo\":1}', NULL),
('LSKH101', 'KHSXX101', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-01 16:00:00', '{"tiendo":"20%"}', 'YCK101'),
('LSKH102', 'KHSXX102', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-02 16:00:00', '{"tiendo":"21%"}', NULL),
('LSKH103', 'KHSXX103', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-03 16:00:00', '{"tiendo":"22%"}', NULL),
('LSKH104', 'KHSXX104', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-04 16:00:00', '{"tiendo":"23%"}', 'YCK104'),
('LSKH105', 'KHSXX105', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-05 16:00:00', '{"tiendo":"24%"}', NULL),
('LSKH106', 'KHSXX106', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-06 16:00:00', '{"tiendo":"25%"}', NULL),
('LSKH107', 'KHSXX107', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-07 16:00:00', '{"tiendo":"26%"}', 'YCK107'),
('LSKH108', 'KHSXX108', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-08 16:00:00', '{"tiendo":"27%"}', NULL),
('LSKH109', 'KHSXX109', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-09 16:00:00', '{"tiendo":"28%"}', NULL),
('LSKH110', 'KHSXX110', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-10 16:00:00', '{"tiendo":"29%"}', 'YCK110'),
('LSKH111', 'KHSXX111', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-11 16:00:00', '{"tiendo":"30%"}', NULL),
('LSKH112', 'KHSXX112', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-12 16:00:00', '{"tiendo":"31%"}', NULL),
('LSKH113', 'KHSXX113', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-13 16:00:00', '{"tiendo":"32%"}', 'YCK113'),
('LSKH114', 'KHSXX114', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-14 16:00:00', '{"tiendo":"33%"}', NULL),
('LSKH115', 'KHSXX115', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-15 16:00:00', '{"tiendo":"34%"}', NULL),
('LSKH116', 'KHSXX116', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-16 16:00:00', '{"tiendo":"35%"}', 'YCK116'),
('LSKH117', 'KHSXX117', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-17 16:00:00', '{"tiendo":"36%"}', NULL),
('LSKH118', 'KHSXX118', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-18 16:00:00', '{"tiendo":"37%"}', NULL),
('LSKH119', 'KHSXX119', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-19 16:00:00', '{"tiendo":"38%"}', 'YCK119'),
('LSKH120', 'KHSXX120', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-20 16:00:00', '{"tiendo":"39%"}', NULL),
('LSKH121', 'KHSXX121', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-21 16:00:00', '{"tiendo":"40%"}', NULL),
('LSKH122', 'KHSXX122', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-22 16:00:00', '{"tiendo":"41%"}', 'YCK122'),
('LSKH123', 'KHSXX123', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-23 16:00:00', '{"tiendo":"42%"}', NULL),
('LSKH124', 'KHSXX124', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-24 16:00:00', '{"tiendo":"43%"}', NULL),
('LSKH125', 'KHSXX125', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-25 16:00:00', '{"tiendo":"44%"}', 'YCK125'),
('LSKH126', 'KHSXX126', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-01 16:00:00', '{"tiendo":"45%"}', NULL),
('LSKH127', 'KHSXX127', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-02 16:00:00', '{"tiendo":"46%"}', NULL),
('LSKH128', 'KHSXX128', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-03 16:00:00', '{"tiendo":"47%"}', 'YCK128'),
('LSKH129', 'KHSXX129', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-04 16:00:00', '{"tiendo":"48%"}', NULL),
('LSKH130', 'KHSXX130', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-05 16:00:00', '{"tiendo":"49%"}', NULL),
('LSKH131', 'KHSXX131', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-06 16:00:00', '{"tiendo":"50%"}', 'YCK131'),
('LSKH132', 'KHSXX132', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-07 16:00:00', '{"tiendo":"51%"}', NULL),
('LSKH133', 'KHSXX133', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-08 16:00:00', '{"tiendo":"52%"}', NULL),
('LSKH134', 'KHSXX134', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-09 16:00:00', '{"tiendo":"53%"}', 'YCK134'),
('LSKH135', 'KHSXX135', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-10 16:00:00', '{"tiendo":"54%"}', NULL),
('LSKH136', 'KHSXX136', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-11 16:00:00', '{"tiendo":"55%"}', NULL),
('LSKH137', 'KHSXX137', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-12 16:00:00', '{"tiendo":"56%"}', 'YCK137'),
('LSKH138', 'KHSXX138', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-13 16:00:00', '{"tiendo":"57%"}', NULL),
('LSKH139', 'KHSXX139', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-14 16:00:00', '{"tiendo":"58%"}', NULL),
('LSKH140', 'KHSXX140', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-15 16:00:00', '{"tiendo":"59%"}', 'YCK140'),
('LSKH141', 'KHSXX141', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-16 16:00:00', '{"tiendo":"60%"}', NULL),
('LSKH142', 'KHSXX142', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-17 16:00:00', '{"tiendo":"61%"}', NULL),
('LSKH143', 'KHSXX143', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-18 16:00:00', '{"tiendo":"62%"}', 'YCK143'),
('LSKH144', 'KHSXX144', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-19 16:00:00', '{"tiendo":"63%"}', NULL),
('LSKH145', 'KHSXX145', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-20 16:00:00', '{"tiendo":"64%"}', NULL),
('LSKH146', 'KHSXX146', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-21 16:00:00', '{"tiendo":"65%"}', 'YCK146'),
('LSKH147', 'KHSXX147', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-22 16:00:00', '{"tiendo":"66%"}', NULL),
('LSKH148', 'KHSXX148', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-23 16:00:00', '{"tiendo":"67%"}', NULL),
('LSKH149', 'KHSXX149', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-24 16:00:00', '{"tiendo":"68%"}', 'YCK149'),
('LSKH150', 'KHSXX150', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-25 16:00:00', '{"tiendo":"69%"}', NULL),
('LSKH151', 'KHSXX151', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-01 16:00:00', '{"tiendo":"70%"}', NULL),
('LSKH152', 'KHSXX152', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-02 16:00:00', '{"tiendo":"71%"}', 'YCK152'),
('LSKH153', 'KHSXX153', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-03 16:00:00', '{"tiendo":"72%"}', NULL),
('LSKH154', 'KHSXX154', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-04 16:00:00', '{"tiendo":"73%"}', NULL),
('LSKH155', 'KHSXX155', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-05 16:00:00', '{"tiendo":"74%"}', 'YCK155'),
('LSKH156', 'KHSXX156', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-06 16:00:00', '{"tiendo":"75%"}', NULL),
('LSKH157', 'KHSXX157', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-07 16:00:00', '{"tiendo":"76%"}', NULL),
('LSKH158', 'KHSXX158', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-08 16:00:00', '{"tiendo":"77%"}', 'YCK158'),
('LSKH159', 'KHSXX159', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-09 16:00:00', '{"tiendo":"78%"}', NULL),
('LSKH160', 'KHSXX160', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-10 16:00:00', '{"tiendo":"79%"}', NULL),
('LSKH161', 'KHSXX161', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-11 16:00:00', '{"tiendo":"80%"}', 'YCK161'),
('LSKH162', 'KHSXX162', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-12 16:00:00', '{"tiendo":"81%"}', NULL),
('LSKH163', 'KHSXX163', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-13 16:00:00', '{"tiendo":"82%"}', NULL),
('LSKH164', 'KHSXX164', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-14 16:00:00', '{"tiendo":"83%"}', 'YCK164'),
('LSKH165', 'KHSXX165', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-15 16:00:00', '{"tiendo":"84%"}', NULL),
('LSKH166', 'KHSXX166', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-16 16:00:00', '{"tiendo":"85%"}', NULL),
('LSKH167', 'KHSXX167', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-17 16:00:00', '{"tiendo":"86%"}', 'YCK167'),
('LSKH168', 'KHSXX168', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-18 16:00:00', '{"tiendo":"87%"}', NULL),
('LSKH169', 'KHSXX169', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-19 16:00:00', '{"tiendo":"88%"}', NULL),
('LSKH170', 'KHSXX170', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-20 16:00:00', '{"tiendo":"89%"}', 'YCK170'),
('LSKH171', 'KHSXX171', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-21 16:00:00', '{"tiendo":"90%"}', NULL),
('LSKH172', 'KHSXX172', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-22 16:00:00', '{"tiendo":"91%"}', NULL),
('LSKH173', 'KHSXX173', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-23 16:00:00', '{"tiendo":"92%"}', 'YCK173'),
('LSKH174', 'KHSXX174', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-24 16:00:00', '{"tiendo":"93%"}', NULL),
('LSKH175', 'KHSXX175', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-25 16:00:00', '{"tiendo":"94%"}', NULL),
('LSKH176', 'KHSXX176', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-01 16:00:00', '{"tiendo":"95%"}', 'YCK176'),
('LSKH177', 'KHSXX177', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-02 16:00:00', '{"tiendo":"96%"}', NULL),
('LSKH178', 'KHSXX178', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-03 16:00:00', '{"tiendo":"97%"}', NULL),
('LSKH179', 'KHSXX179', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-04 16:00:00', '{"tiendo":"98%"}', 'YCK179'),
('LSKH180', 'KHSXX180', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-05 16:00:00', '{"tiendo":"99%"}', NULL),
('LSKH181', 'KHSXX181', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-06 16:00:00', '{"tiendo":"20%"}', NULL),
('LSKH182', 'KHSXX182', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-07 16:00:00', '{"tiendo":"21%"}', 'YCK182'),
('LSKH183', 'KHSXX183', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-08 16:00:00', '{"tiendo":"22%"}', NULL),
('LSKH184', 'KHSXX184', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-09 16:00:00', '{"tiendo":"23%"}', NULL),
('LSKH185', 'KHSXX185', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-10 16:00:00', '{"tiendo":"24%"}', 'YCK185'),
('LSKH186', 'KHSXX186', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-11 16:00:00', '{"tiendo":"25%"}', NULL),
('LSKH187', 'KHSXX187', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-12 16:00:00', '{"tiendo":"26%"}', NULL),
('LSKH188', 'KHSXX188', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-13 16:00:00', '{"tiendo":"27%"}', 'YCK188'),
('LSKH189', 'KHSXX189', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-14 16:00:00', '{"tiendo":"28%"}', NULL),
('LSKH190', 'KHSXX190', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-15 16:00:00', '{"tiendo":"29%"}', NULL),
('LSKH191', 'KHSXX191', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-16 16:00:00', '{"tiendo":"30%"}', 'YCK191'),
('LSKH192', 'KHSXX192', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-17 16:00:00', '{"tiendo":"31%"}', NULL),
('LSKH193', 'KHSXX193', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-18 16:00:00', '{"tiendo":"32%"}', NULL),
('LSKH194', 'KHSXX194', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-19 16:00:00', '{"tiendo":"33%"}', 'YCK194'),
('LSKH195', 'KHSXX195', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-20 16:00:00', '{"tiendo":"34%"}', NULL),
('LSKH196', 'KHSXX196', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-21 16:00:00', '{"tiendo":"35%"}', NULL),
('LSKH197', 'KHSXX197', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-22 16:00:00', '{"tiendo":"36%"}', 'YCK197'),
('LSKH198', 'KHSXX198', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-23 16:00:00', '{"tiendo":"37%"}', NULL),
('LSKH199', 'KHSXX199', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-24 16:00:00', '{"tiendo":"38%"}', NULL),
('LSKH200', 'KHSXX200', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-25 16:00:00', '{"tiendo":"39%"}', 'YCK200'),
('LSKH201', 'KHSXX201', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-01 16:00:00', '{"tiendo":"40%"}', NULL),
('LSKH202', 'KHSXX202', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-02 16:00:00', '{"tiendo":"41%"}', NULL),
('LSKH203', 'KHSXX203', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-03 16:00:00', '{"tiendo":"42%"}', 'YCK203'),
('LSKH204', 'KHSXX204', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-04 16:00:00', '{"tiendo":"43%"}', NULL),
('LSKH205', 'KHSXX205', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-05 16:00:00', '{"tiendo":"44%"}', NULL),
('LSKH206', 'KHSXX206', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-06 16:00:00', '{"tiendo":"45%"}', 'YCK206'),
('LSKH207', 'KHSXX207', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-07 16:00:00', '{"tiendo":"46%"}', NULL),
('LSKH208', 'KHSXX208', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-08 16:00:00', '{"tiendo":"47%"}', NULL),
('LSKH209', 'KHSXX209', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-09 16:00:00', '{"tiendo":"48%"}', 'YCK209'),
('LSKH210', 'KHSXX210', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-10 16:00:00', '{"tiendo":"49%"}', NULL),
('LSKH211', 'KHSXX211', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-11 16:00:00', '{"tiendo":"50%"}', NULL),
('LSKH212', 'KHSXX212', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-12 16:00:00', '{"tiendo":"51%"}', 'YCK212'),
('LSKH213', 'KHSXX213', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-13 16:00:00', '{"tiendo":"52%"}', NULL),
('LSKH214', 'KHSXX214', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-14 16:00:00', '{"tiendo":"53%"}', NULL),
('LSKH215', 'KHSXX215', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-15 16:00:00', '{"tiendo":"54%"}', 'YCK215'),
('LSKH216', 'KHSXX216', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-16 16:00:00', '{"tiendo":"55%"}', NULL),
('LSKH217', 'KHSXX217', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-17 16:00:00', '{"tiendo":"56%"}', NULL),
('LSKH218', 'KHSXX218', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-18 16:00:00', '{"tiendo":"57%"}', 'YCK218'),
('LSKH219', 'KHSXX219', 'Đang sản xuất', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_XU01', '2024-04-19 16:00:00', '{"tiendo":"58%"}', NULL),
('LSKH220', 'KHSXX220', 'Chờ QC', 'Cập nhật tiến độ', 'Theo dõi tiến độ hàng ngày', 'NV_QC01', '2024-04-20 16:00:00', '{"tiendo":"59%"}', NULL);

INSERT INTO `ca_lam` (`IdCaLamViec`, `TenCa`, `LoaiCa`, `NgayLamViec`, `ThoiGianBatDau`, `ThoiGianKetThuc`, `TongSL`, `IdKeHoachSanXuatXuong`, `LOIdLo`) VALUES
('CL001', 'Ca sáng', 'Sáng', '2024-04-10', '2024-04-10 08:00:00', '2024-04-10 17:00:00', 50, 'KHSXX001', 'LO001'),
('CL002', 'Ca chiều', 'Chiều', '2024-04-10', '2024-04-10 13:00:00', '2024-04-10 21:00:00', 50, 'KHSXX001', 'LO001'),
('CL101', 'Ca 101', 'Sáng', '2024-04-01', '2024-04-01 08:00:00', '2024-04-01 17:00:00', 40, 'KHSXX101', 'LO001'),
('CL102', 'Ca 102', 'Sáng', '2024-04-02', '2024-04-02 08:00:00', '2024-04-02 17:00:00', 41, 'KHSXX102', 'LO002'),
('CL103', 'Ca 103', 'Sáng', '2024-04-03', '2024-04-03 08:00:00', '2024-04-03 17:00:00', 42, 'KHSXX103', 'LO003'),
('CL104', 'Ca 104', 'Sáng', '2024-04-04', '2024-04-04 08:00:00', '2024-04-04 17:00:00', 43, 'KHSXX104', 'LO001'),
('CL105', 'Ca 105', 'Sáng', '2024-04-05', '2024-04-05 08:00:00', '2024-04-05 17:00:00', 44, 'KHSXX105', 'LO002'),
('CL106', 'Ca 106', 'Sáng', '2024-04-06', '2024-04-06 08:00:00', '2024-04-06 17:00:00', 45, 'KHSXX106', 'LO003'),
('CL107', 'Ca 107', 'Sáng', '2024-04-07', '2024-04-07 08:00:00', '2024-04-07 17:00:00', 46, 'KHSXX107', 'LO001'),
('CL108', 'Ca 108', 'Sáng', '2024-04-08', '2024-04-08 08:00:00', '2024-04-08 17:00:00', 47, 'KHSXX108', 'LO002'),
('CL109', 'Ca 109', 'Sáng', '2024-04-09', '2024-04-09 08:00:00', '2024-04-09 17:00:00', 48, 'KHSXX109', 'LO003'),
('CL110', 'Ca 110', 'Sáng', '2024-04-10', '2024-04-10 08:00:00', '2024-04-10 17:00:00', 49, 'KHSXX110', 'LO001'),
('CL111', 'Ca 111', 'Sáng', '2024-04-11', '2024-04-11 08:00:00', '2024-04-11 17:00:00', 50, 'KHSXX111', 'LO002'),
('CL112', 'Ca 112', 'Sáng', '2024-04-12', '2024-04-12 08:00:00', '2024-04-12 17:00:00', 51, 'KHSXX112', 'LO003'),
('CL113', 'Ca 113', 'Sáng', '2024-04-13', '2024-04-13 08:00:00', '2024-04-13 17:00:00', 52, 'KHSXX113', 'LO001'),
('CL114', 'Ca 114', 'Sáng', '2024-04-14', '2024-04-14 08:00:00', '2024-04-14 17:00:00', 53, 'KHSXX114', 'LO002'),
('CL115', 'Ca 115', 'Sáng', '2024-04-15', '2024-04-15 08:00:00', '2024-04-15 17:00:00', 54, 'KHSXX115', 'LO003'),
('CL116', 'Ca 116', 'Sáng', '2024-04-16', '2024-04-16 08:00:00', '2024-04-16 17:00:00', 55, 'KHSXX116', 'LO001'),
('CL117', 'Ca 117', 'Sáng', '2024-04-17', '2024-04-17 08:00:00', '2024-04-17 17:00:00', 56, 'KHSXX117', 'LO002'),
('CL118', 'Ca 118', 'Sáng', '2024-04-18', '2024-04-18 08:00:00', '2024-04-18 17:00:00', 57, 'KHSXX118', 'LO003'),
('CL119', 'Ca 119', 'Sáng', '2024-04-19', '2024-04-19 08:00:00', '2024-04-19 17:00:00', 58, 'KHSXX119', 'LO001'),
('CL120', 'Ca 120', 'Sáng', '2024-04-20', '2024-04-20 08:00:00', '2024-04-20 17:00:00', 59, 'KHSXX120', 'LO002'),
('CL121', 'Ca 121', 'Sáng', '2024-04-21', '2024-04-21 08:00:00', '2024-04-21 17:00:00', 40, 'KHSXX121', 'LO003'),
('CL122', 'Ca 122', 'Sáng', '2024-04-22', '2024-04-22 08:00:00', '2024-04-22 17:00:00', 41, 'KHSXX122', 'LO001'),
('CL123', 'Ca 123', 'Sáng', '2024-04-23', '2024-04-23 08:00:00', '2024-04-23 17:00:00', 42, 'KHSXX123', 'LO002'),
('CL124', 'Ca 124', 'Sáng', '2024-04-24', '2024-04-24 08:00:00', '2024-04-24 17:00:00', 43, 'KHSXX124', 'LO003'),
('CL125', 'Ca 125', 'Sáng', '2024-04-25', '2024-04-25 08:00:00', '2024-04-25 17:00:00', 44, 'KHSXX125', 'LO001'),
('CL126', 'Ca 126', 'Sáng', '2024-04-26', '2024-04-26 08:00:00', '2024-04-26 17:00:00', 45, 'KHSXX126', 'LO002'),
('CL127', 'Ca 127', 'Sáng', '2024-04-27', '2024-04-27 08:00:00', '2024-04-27 17:00:00', 46, 'KHSXX127', 'LO003'),
('CL128', 'Ca 128', 'Sáng', '2024-04-28', '2024-04-28 08:00:00', '2024-04-28 17:00:00', 47, 'KHSXX128', 'LO001'),
('CL129', 'Ca 129', 'Sáng', '2024-04-01', '2024-04-01 08:00:00', '2024-04-01 17:00:00', 48, 'KHSXX129', 'LO002'),
('CL130', 'Ca 130', 'Sáng', '2024-04-02', '2024-04-02 08:00:00', '2024-04-02 17:00:00', 49, 'KHSXX130', 'LO003'),
('CL131', 'Ca 131', 'Sáng', '2024-04-03', '2024-04-03 08:00:00', '2024-04-03 17:00:00', 50, 'KHSXX131', 'LO001'),
('CL132', 'Ca 132', 'Sáng', '2024-04-04', '2024-04-04 08:00:00', '2024-04-04 17:00:00', 51, 'KHSXX132', 'LO002'),
('CL133', 'Ca 133', 'Sáng', '2024-04-05', '2024-04-05 08:00:00', '2024-04-05 17:00:00', 52, 'KHSXX133', 'LO003'),
('CL134', 'Ca 134', 'Sáng', '2024-04-06', '2024-04-06 08:00:00', '2024-04-06 17:00:00', 53, 'KHSXX134', 'LO001'),
('CL135', 'Ca 135', 'Sáng', '2024-04-07', '2024-04-07 08:00:00', '2024-04-07 17:00:00', 54, 'KHSXX135', 'LO002'),
('CL136', 'Ca 136', 'Sáng', '2024-04-08', '2024-04-08 08:00:00', '2024-04-08 17:00:00', 55, 'KHSXX136', 'LO003'),
('CL137', 'Ca 137', 'Sáng', '2024-04-09', '2024-04-09 08:00:00', '2024-04-09 17:00:00', 56, 'KHSXX137', 'LO001'),
('CL138', 'Ca 138', 'Sáng', '2024-04-10', '2024-04-10 08:00:00', '2024-04-10 17:00:00', 57, 'KHSXX138', 'LO002'),
('CL139', 'Ca 139', 'Sáng', '2024-04-11', '2024-04-11 08:00:00', '2024-04-11 17:00:00', 58, 'KHSXX139', 'LO003'),
('CL140', 'Ca 140', 'Sáng', '2024-04-12', '2024-04-12 08:00:00', '2024-04-12 17:00:00', 59, 'KHSXX140', 'LO001'),
('CL141', 'Ca 141', 'Sáng', '2024-04-13', '2024-04-13 08:00:00', '2024-04-13 17:00:00', 40, 'KHSXX141', 'LO002'),
('CL142', 'Ca 142', 'Sáng', '2024-04-14', '2024-04-14 08:00:00', '2024-04-14 17:00:00', 41, 'KHSXX142', 'LO003'),
('CL143', 'Ca 143', 'Sáng', '2024-04-15', '2024-04-15 08:00:00', '2024-04-15 17:00:00', 42, 'KHSXX143', 'LO001'),
('CL144', 'Ca 144', 'Sáng', '2024-04-16', '2024-04-16 08:00:00', '2024-04-16 17:00:00', 43, 'KHSXX144', 'LO002'),
('CL145', 'Ca 145', 'Sáng', '2024-04-17', '2024-04-17 08:00:00', '2024-04-17 17:00:00', 44, 'KHSXX145', 'LO003'),
('CL146', 'Ca 146', 'Sáng', '2024-04-18', '2024-04-18 08:00:00', '2024-04-18 17:00:00', 45, 'KHSXX146', 'LO001'),
('CL147', 'Ca 147', 'Sáng', '2024-04-19', '2024-04-19 08:00:00', '2024-04-19 17:00:00', 46, 'KHSXX147', 'LO002'),
('CL148', 'Ca 148', 'Sáng', '2024-04-20', '2024-04-20 08:00:00', '2024-04-20 17:00:00', 47, 'KHSXX148', 'LO003'),
('CL149', 'Ca 149', 'Sáng', '2024-04-21', '2024-04-21 08:00:00', '2024-04-21 17:00:00', 48, 'KHSXX149', 'LO001'),
('CL150', 'Ca 150', 'Sáng', '2024-04-22', '2024-04-22 08:00:00', '2024-04-22 17:00:00', 49, 'KHSXX150', 'LO002'),
('CL151', 'Ca 151', 'Sáng', '2024-04-23', '2024-04-23 08:00:00', '2024-04-23 17:00:00', 50, 'KHSXX151', 'LO003'),
('CL152', 'Ca 152', 'Sáng', '2024-04-24', '2024-04-24 08:00:00', '2024-04-24 17:00:00', 51, 'KHSXX152', 'LO001'),
('CL153', 'Ca 153', 'Sáng', '2024-04-25', '2024-04-25 08:00:00', '2024-04-25 17:00:00', 52, 'KHSXX153', 'LO002'),
('CL154', 'Ca 154', 'Sáng', '2024-04-26', '2024-04-26 08:00:00', '2024-04-26 17:00:00', 53, 'KHSXX154', 'LO003'),
('CL155', 'Ca 155', 'Sáng', '2024-04-27', '2024-04-27 08:00:00', '2024-04-27 17:00:00', 54, 'KHSXX155', 'LO001'),
('CL156', 'Ca 156', 'Sáng', '2024-04-28', '2024-04-28 08:00:00', '2024-04-28 17:00:00', 55, 'KHSXX156', 'LO002'),
('CL157', 'Ca 157', 'Sáng', '2024-04-01', '2024-04-01 08:00:00', '2024-04-01 17:00:00', 56, 'KHSXX157', 'LO003'),
('CL158', 'Ca 158', 'Sáng', '2024-04-02', '2024-04-02 08:00:00', '2024-04-02 17:00:00', 57, 'KHSXX158', 'LO001'),
('CL159', 'Ca 159', 'Sáng', '2024-04-03', '2024-04-03 08:00:00', '2024-04-03 17:00:00', 58, 'KHSXX159', 'LO002'),
('CL160', 'Ca 160', 'Sáng', '2024-04-04', '2024-04-04 08:00:00', '2024-04-04 17:00:00', 59, 'KHSXX160', 'LO003'),
('CL161', 'Ca 161', 'Sáng', '2024-04-05', '2024-04-05 08:00:00', '2024-04-05 17:00:00', 40, 'KHSXX161', 'LO001'),
('CL162', 'Ca 162', 'Sáng', '2024-04-06', '2024-04-06 08:00:00', '2024-04-06 17:00:00', 41, 'KHSXX162', 'LO002'),
('CL163', 'Ca 163', 'Sáng', '2024-04-07', '2024-04-07 08:00:00', '2024-04-07 17:00:00', 42, 'KHSXX163', 'LO003'),
('CL164', 'Ca 164', 'Sáng', '2024-04-08', '2024-04-08 08:00:00', '2024-04-08 17:00:00', 43, 'KHSXX164', 'LO001'),
('CL165', 'Ca 165', 'Sáng', '2024-04-09', '2024-04-09 08:00:00', '2024-04-09 17:00:00', 44, 'KHSXX165', 'LO002'),
('CL166', 'Ca 166', 'Sáng', '2024-04-10', '2024-04-10 08:00:00', '2024-04-10 17:00:00', 45, 'KHSXX166', 'LO003'),
('CL167', 'Ca 167', 'Sáng', '2024-04-11', '2024-04-11 08:00:00', '2024-04-11 17:00:00', 46, 'KHSXX167', 'LO001'),
('CL168', 'Ca 168', 'Sáng', '2024-04-12', '2024-04-12 08:00:00', '2024-04-12 17:00:00', 47, 'KHSXX168', 'LO002'),
('CL169', 'Ca 169', 'Sáng', '2024-04-13', '2024-04-13 08:00:00', '2024-04-13 17:00:00', 48, 'KHSXX169', 'LO003'),
('CL170', 'Ca 170', 'Sáng', '2024-04-14', '2024-04-14 08:00:00', '2024-04-14 17:00:00', 49, 'KHSXX170', 'LO001'),
('CL171', 'Ca 171', 'Sáng', '2024-04-15', '2024-04-15 08:00:00', '2024-04-15 17:00:00', 50, 'KHSXX171', 'LO002'),
('CL172', 'Ca 172', 'Sáng', '2024-04-16', '2024-04-16 08:00:00', '2024-04-16 17:00:00', 51, 'KHSXX172', 'LO003'),
('CL173', 'Ca 173', 'Sáng', '2024-04-17', '2024-04-17 08:00:00', '2024-04-17 17:00:00', 52, 'KHSXX173', 'LO001'),
('CL174', 'Ca 174', 'Sáng', '2024-04-18', '2024-04-18 08:00:00', '2024-04-18 17:00:00', 53, 'KHSXX174', 'LO002'),
('CL175', 'Ca 175', 'Sáng', '2024-04-19', '2024-04-19 08:00:00', '2024-04-19 17:00:00', 54, 'KHSXX175', 'LO003'),
('CL176', 'Ca 176', 'Sáng', '2024-04-20', '2024-04-20 08:00:00', '2024-04-20 17:00:00', 55, 'KHSXX176', 'LO001'),
('CL177', 'Ca 177', 'Sáng', '2024-04-21', '2024-04-21 08:00:00', '2024-04-21 17:00:00', 56, 'KHSXX177', 'LO002'),
('CL178', 'Ca 178', 'Sáng', '2024-04-22', '2024-04-22 08:00:00', '2024-04-22 17:00:00', 57, 'KHSXX178', 'LO003'),
('CL179', 'Ca 179', 'Sáng', '2024-04-23', '2024-04-23 08:00:00', '2024-04-23 17:00:00', 58, 'KHSXX179', 'LO001'),
('CL180', 'Ca 180', 'Sáng', '2024-04-24', '2024-04-24 08:00:00', '2024-04-24 17:00:00', 59, 'KHSXX180', 'LO002'),
('CL181', 'Ca 181', 'Sáng', '2024-04-25', '2024-04-25 08:00:00', '2024-04-25 17:00:00', 40, 'KHSXX181', 'LO003'),
('CL182', 'Ca 182', 'Sáng', '2024-04-26', '2024-04-26 08:00:00', '2024-04-26 17:00:00', 41, 'KHSXX182', 'LO001'),
('CL183', 'Ca 183', 'Sáng', '2024-04-27', '2024-04-27 08:00:00', '2024-04-27 17:00:00', 42, 'KHSXX183', 'LO002'),
('CL184', 'Ca 184', 'Sáng', '2024-04-28', '2024-04-28 08:00:00', '2024-04-28 17:00:00', 43, 'KHSXX184', 'LO003'),
('CL185', 'Ca 185', 'Sáng', '2024-04-01', '2024-04-01 08:00:00', '2024-04-01 17:00:00', 44, 'KHSXX185', 'LO001'),
('CL186', 'Ca 186', 'Sáng', '2024-04-02', '2024-04-02 08:00:00', '2024-04-02 17:00:00', 45, 'KHSXX186', 'LO002'),
('CL187', 'Ca 187', 'Sáng', '2024-04-03', '2024-04-03 08:00:00', '2024-04-03 17:00:00', 46, 'KHSXX187', 'LO003'),
('CL188', 'Ca 188', 'Sáng', '2024-04-04', '2024-04-04 08:00:00', '2024-04-04 17:00:00', 47, 'KHSXX188', 'LO001'),
('CL189', 'Ca 189', 'Sáng', '2024-04-05', '2024-04-05 08:00:00', '2024-04-05 17:00:00', 48, 'KHSXX189', 'LO002'),
('CL190', 'Ca 190', 'Sáng', '2024-04-06', '2024-04-06 08:00:00', '2024-04-06 17:00:00', 49, 'KHSXX190', 'LO003'),
('CL191', 'Ca 191', 'Sáng', '2024-04-07', '2024-04-07 08:00:00', '2024-04-07 17:00:00', 50, 'KHSXX191', 'LO001'),
('CL192', 'Ca 192', 'Sáng', '2024-04-08', '2024-04-08 08:00:00', '2024-04-08 17:00:00', 51, 'KHSXX192', 'LO002'),
('CL193', 'Ca 193', 'Sáng', '2024-04-09', '2024-04-09 08:00:00', '2024-04-09 17:00:00', 52, 'KHSXX193', 'LO003'),
('CL194', 'Ca 194', 'Sáng', '2024-04-10', '2024-04-10 08:00:00', '2024-04-10 17:00:00', 53, 'KHSXX194', 'LO001'),
('CL195', 'Ca 195', 'Sáng', '2024-04-11', '2024-04-11 08:00:00', '2024-04-11 17:00:00', 54, 'KHSXX195', 'LO002'),
('CL196', 'Ca 196', 'Sáng', '2024-04-12', '2024-04-12 08:00:00', '2024-04-12 17:00:00', 55, 'KHSXX196', 'LO003'),
('CL197', 'Ca 197', 'Sáng', '2024-04-13', '2024-04-13 08:00:00', '2024-04-13 17:00:00', 56, 'KHSXX197', 'LO001'),
('CL198', 'Ca 198', 'Sáng', '2024-04-14', '2024-04-14 08:00:00', '2024-04-14 17:00:00', 57, 'KHSXX198', 'LO002'),
('CL199', 'Ca 199', 'Sáng', '2024-04-15', '2024-04-15 08:00:00', '2024-04-15 17:00:00', 58, 'KHSXX199', 'LO003'),
('CL200', 'Ca 200', 'Sáng', '2024-04-16', '2024-04-16 08:00:00', '2024-04-16 17:00:00', 59, 'KHSXX200', 'LO001'),
('CL201', 'Ca 201', 'Sáng', '2024-04-17', '2024-04-17 08:00:00', '2024-04-17 17:00:00', 40, 'KHSXX201', 'LO002'),
('CL202', 'Ca 202', 'Sáng', '2024-04-18', '2024-04-18 08:00:00', '2024-04-18 17:00:00', 41, 'KHSXX202', 'LO003'),
('CL203', 'Ca 203', 'Sáng', '2024-04-19', '2024-04-19 08:00:00', '2024-04-19 17:00:00', 42, 'KHSXX203', 'LO001'),
('CL204', 'Ca 204', 'Sáng', '2024-04-20', '2024-04-20 08:00:00', '2024-04-20 17:00:00', 43, 'KHSXX204', 'LO002'),
('CL205', 'Ca 205', 'Sáng', '2024-04-21', '2024-04-21 08:00:00', '2024-04-21 17:00:00', 44, 'KHSXX205', 'LO003'),
('CL206', 'Ca 206', 'Sáng', '2024-04-22', '2024-04-22 08:00:00', '2024-04-22 17:00:00', 45, 'KHSXX206', 'LO001'),
('CL207', 'Ca 207', 'Sáng', '2024-04-23', '2024-04-23 08:00:00', '2024-04-23 17:00:00', 46, 'KHSXX207', 'LO002'),
('CL208', 'Ca 208', 'Sáng', '2024-04-24', '2024-04-24 08:00:00', '2024-04-24 17:00:00', 47, 'KHSXX208', 'LO003'),
('CL209', 'Ca 209', 'Sáng', '2024-04-25', '2024-04-25 08:00:00', '2024-04-25 17:00:00', 48, 'KHSXX209', 'LO001'),
('CL210', 'Ca 210', 'Sáng', '2024-04-26', '2024-04-26 08:00:00', '2024-04-26 17:00:00', 49, 'KHSXX210', 'LO002'),
('CL211', 'Ca 211', 'Sáng', '2024-04-27', '2024-04-27 08:00:00', '2024-04-27 17:00:00', 50, 'KHSXX211', 'LO003'),
('CL212', 'Ca 212', 'Sáng', '2024-04-28', '2024-04-28 08:00:00', '2024-04-28 17:00:00', 51, 'KHSXX212', 'LO001'),
('CL213', 'Ca 213', 'Sáng', '2024-04-01', '2024-04-01 08:00:00', '2024-04-01 17:00:00', 52, 'KHSXX213', 'LO002'),
('CL214', 'Ca 214', 'Sáng', '2024-04-02', '2024-04-02 08:00:00', '2024-04-02 17:00:00', 53, 'KHSXX214', 'LO003'),
('CL215', 'Ca 215', 'Sáng', '2024-04-03', '2024-04-03 08:00:00', '2024-04-03 17:00:00', 54, 'KHSXX215', 'LO001'),
('CL216', 'Ca 216', 'Sáng', '2024-04-04', '2024-04-04 08:00:00', '2024-04-04 17:00:00', 55, 'KHSXX216', 'LO002'),
('CL217', 'Ca 217', 'Sáng', '2024-04-05', '2024-04-05 08:00:00', '2024-04-05 17:00:00', 56, 'KHSXX217', 'LO003'),
('CL218', 'Ca 218', 'Sáng', '2024-04-06', '2024-04-06 08:00:00', '2024-04-06 17:00:00', 57, 'KHSXX218', 'LO001'),
('CL219', 'Ca 219', 'Sáng', '2024-04-07', '2024-04-07 08:00:00', '2024-04-07 17:00:00', 58, 'KHSXX219', 'LO002'),
('CL220', 'Ca 220', 'Sáng', '2024-04-08', '2024-04-08 08:00:00', '2024-04-08 17:00:00', 59, 'KHSXX220', 'LO003');

INSERT INTO `phan_cong_ke_hoach_xuong` (`IdPhanCong`, `IdKeHoachSanXuatXuong`, `IdNhanVien`, `IdCaLamViec`, `VaiTro`, `NgayPhanCong`) VALUES
('PCX001', 'KHSXX001', 'NV_SX01', 'CL001', 'Lắp ráp', '2024-04-09 15:00:00'),
('PCX002', 'KHSXX001', 'NV_SX02', 'CL002', 'Kiểm tra sơ bộ', '2024-04-09 15:30:00'),
('PCX101', 'KHSXX101', 'NV101', 'CL101', 'Lắp ráp', '2024-04-01 14:00:00'),
('PCX102', 'KHSXX102', 'NV102', 'CL102', 'Kiểm tra', '2024-04-02 14:00:00'),
('PCX103', 'KHSXX103', 'NV103', 'CL103', 'Lắp ráp', '2024-04-03 14:00:00'),
('PCX104', 'KHSXX104', 'NV104', 'CL104', 'Kiểm tra', '2024-04-04 14:00:00'),
('PCX105', 'KHSXX105', 'NV105', 'CL105', 'Lắp ráp', '2024-04-05 14:00:00'),
('PCX106', 'KHSXX106', 'NV106', 'CL106', 'Kiểm tra', '2024-04-06 14:00:00'),
('PCX107', 'KHSXX107', 'NV107', 'CL107', 'Lắp ráp', '2024-04-07 14:00:00'),
('PCX108', 'KHSXX108', 'NV108', 'CL108', 'Kiểm tra', '2024-04-08 14:00:00'),
('PCX109', 'KHSXX109', 'NV109', 'CL109', 'Lắp ráp', '2024-04-09 14:00:00'),
('PCX110', 'KHSXX110', 'NV110', 'CL110', 'Kiểm tra', '2024-04-10 14:00:00'),
('PCX111', 'KHSXX111', 'NV111', 'CL111', 'Lắp ráp', '2024-04-11 14:00:00'),
('PCX112', 'KHSXX112', 'NV112', 'CL112', 'Kiểm tra', '2024-04-12 14:00:00'),
('PCX113', 'KHSXX113', 'NV113', 'CL113', 'Lắp ráp', '2024-04-13 14:00:00'),
('PCX114', 'KHSXX114', 'NV114', 'CL114', 'Kiểm tra', '2024-04-14 14:00:00'),
('PCX115', 'KHSXX115', 'NV115', 'CL115', 'Lắp ráp', '2024-04-15 14:00:00'),
('PCX116', 'KHSXX116', 'NV116', 'CL116', 'Kiểm tra', '2024-04-16 14:00:00'),
('PCX117', 'KHSXX117', 'NV117', 'CL117', 'Lắp ráp', '2024-04-17 14:00:00'),
('PCX118', 'KHSXX118', 'NV118', 'CL118', 'Kiểm tra', '2024-04-18 14:00:00'),
('PCX119', 'KHSXX119', 'NV119', 'CL119', 'Lắp ráp', '2024-04-19 14:00:00'),
('PCX120', 'KHSXX120', 'NV120', 'CL120', 'Kiểm tra', '2024-04-20 14:00:00'),
('PCX121', 'KHSXX121', 'NV121', 'CL121', 'Lắp ráp', '2024-04-21 14:00:00'),
('PCX122', 'KHSXX122', 'NV122', 'CL122', 'Kiểm tra', '2024-04-22 14:00:00'),
('PCX123', 'KHSXX123', 'NV123', 'CL123', 'Lắp ráp', '2024-04-23 14:00:00'),
('PCX124', 'KHSXX124', 'NV124', 'CL124', 'Kiểm tra', '2024-04-24 14:00:00'),
('PCX125', 'KHSXX125', 'NV125', 'CL125', 'Lắp ráp', '2024-04-25 14:00:00'),
('PCX126', 'KHSXX126', 'NV126', 'CL126', 'Kiểm tra', '2024-04-01 14:00:00'),
('PCX127', 'KHSXX127', 'NV127', 'CL127', 'Lắp ráp', '2024-04-02 14:00:00'),
('PCX128', 'KHSXX128', 'NV128', 'CL128', 'Kiểm tra', '2024-04-03 14:00:00'),
('PCX129', 'KHSXX129', 'NV129', 'CL129', 'Lắp ráp', '2024-04-04 14:00:00'),
('PCX130', 'KHSXX130', 'NV130', 'CL130', 'Kiểm tra', '2024-04-05 14:00:00'),
('PCX131', 'KHSXX131', 'NV131', 'CL131', 'Lắp ráp', '2024-04-06 14:00:00'),
('PCX132', 'KHSXX132', 'NV132', 'CL132', 'Kiểm tra', '2024-04-07 14:00:00'),
('PCX133', 'KHSXX133', 'NV133', 'CL133', 'Lắp ráp', '2024-04-08 14:00:00'),
('PCX134', 'KHSXX134', 'NV134', 'CL134', 'Kiểm tra', '2024-04-09 14:00:00'),
('PCX135', 'KHSXX135', 'NV135', 'CL135', 'Lắp ráp', '2024-04-10 14:00:00'),
('PCX136', 'KHSXX136', 'NV136', 'CL136', 'Kiểm tra', '2024-04-11 14:00:00'),
('PCX137', 'KHSXX137', 'NV137', 'CL137', 'Lắp ráp', '2024-04-12 14:00:00'),
('PCX138', 'KHSXX138', 'NV138', 'CL138', 'Kiểm tra', '2024-04-13 14:00:00'),
('PCX139', 'KHSXX139', 'NV139', 'CL139', 'Lắp ráp', '2024-04-14 14:00:00'),
('PCX140', 'KHSXX140', 'NV140', 'CL140', 'Kiểm tra', '2024-04-15 14:00:00'),
('PCX141', 'KHSXX141', 'NV141', 'CL141', 'Lắp ráp', '2024-04-16 14:00:00'),
('PCX142', 'KHSXX142', 'NV142', 'CL142', 'Kiểm tra', '2024-04-17 14:00:00'),
('PCX143', 'KHSXX143', 'NV143', 'CL143', 'Lắp ráp', '2024-04-18 14:00:00'),
('PCX144', 'KHSXX144', 'NV144', 'CL144', 'Kiểm tra', '2024-04-19 14:00:00'),
('PCX145', 'KHSXX145', 'NV145', 'CL145', 'Lắp ráp', '2024-04-20 14:00:00'),
('PCX146', 'KHSXX146', 'NV146', 'CL146', 'Kiểm tra', '2024-04-21 14:00:00'),
('PCX147', 'KHSXX147', 'NV147', 'CL147', 'Lắp ráp', '2024-04-22 14:00:00'),
('PCX148', 'KHSXX148', 'NV148', 'CL148', 'Kiểm tra', '2024-04-23 14:00:00'),
('PCX149', 'KHSXX149', 'NV149', 'CL149', 'Lắp ráp', '2024-04-24 14:00:00'),
('PCX150', 'KHSXX150', 'NV150', 'CL150', 'Kiểm tra', '2024-04-25 14:00:00'),
('PCX151', 'KHSXX151', 'NV151', 'CL151', 'Lắp ráp', '2024-04-01 14:00:00'),
('PCX152', 'KHSXX152', 'NV152', 'CL152', 'Kiểm tra', '2024-04-02 14:00:00'),
('PCX153', 'KHSXX153', 'NV153', 'CL153', 'Lắp ráp', '2024-04-03 14:00:00'),
('PCX154', 'KHSXX154', 'NV154', 'CL154', 'Kiểm tra', '2024-04-04 14:00:00'),
('PCX155', 'KHSXX155', 'NV155', 'CL155', 'Lắp ráp', '2024-04-05 14:00:00'),
('PCX156', 'KHSXX156', 'NV156', 'CL156', 'Kiểm tra', '2024-04-06 14:00:00'),
('PCX157', 'KHSXX157', 'NV157', 'CL157', 'Lắp ráp', '2024-04-07 14:00:00'),
('PCX158', 'KHSXX158', 'NV158', 'CL158', 'Kiểm tra', '2024-04-08 14:00:00'),
('PCX159', 'KHSXX159', 'NV159', 'CL159', 'Lắp ráp', '2024-04-09 14:00:00'),
('PCX160', 'KHSXX160', 'NV160', 'CL160', 'Kiểm tra', '2024-04-10 14:00:00'),
('PCX161', 'KHSXX161', 'NV161', 'CL161', 'Lắp ráp', '2024-04-11 14:00:00'),
('PCX162', 'KHSXX162', 'NV162', 'CL162', 'Kiểm tra', '2024-04-12 14:00:00'),
('PCX163', 'KHSXX163', 'NV163', 'CL163', 'Lắp ráp', '2024-04-13 14:00:00'),
('PCX164', 'KHSXX164', 'NV164', 'CL164', 'Kiểm tra', '2024-04-14 14:00:00'),
('PCX165', 'KHSXX165', 'NV165', 'CL165', 'Lắp ráp', '2024-04-15 14:00:00'),
('PCX166', 'KHSXX166', 'NV166', 'CL166', 'Kiểm tra', '2024-04-16 14:00:00'),
('PCX167', 'KHSXX167', 'NV167', 'CL167', 'Lắp ráp', '2024-04-17 14:00:00'),
('PCX168', 'KHSXX168', 'NV168', 'CL168', 'Kiểm tra', '2024-04-18 14:00:00'),
('PCX169', 'KHSXX169', 'NV169', 'CL169', 'Lắp ráp', '2024-04-19 14:00:00'),
('PCX170', 'KHSXX170', 'NV170', 'CL170', 'Kiểm tra', '2024-04-20 14:00:00'),
('PCX171', 'KHSXX171', 'NV171', 'CL171', 'Lắp ráp', '2024-04-21 14:00:00'),
('PCX172', 'KHSXX172', 'NV172', 'CL172', 'Kiểm tra', '2024-04-22 14:00:00'),
('PCX173', 'KHSXX173', 'NV173', 'CL173', 'Lắp ráp', '2024-04-23 14:00:00'),
('PCX174', 'KHSXX174', 'NV174', 'CL174', 'Kiểm tra', '2024-04-24 14:00:00'),
('PCX175', 'KHSXX175', 'NV175', 'CL175', 'Lắp ráp', '2024-04-25 14:00:00'),
('PCX176', 'KHSXX176', 'NV176', 'CL176', 'Kiểm tra', '2024-04-01 14:00:00'),
('PCX177', 'KHSXX177', 'NV177', 'CL177', 'Lắp ráp', '2024-04-02 14:00:00'),
('PCX178', 'KHSXX178', 'NV178', 'CL178', 'Kiểm tra', '2024-04-03 14:00:00'),
('PCX179', 'KHSXX179', 'NV179', 'CL179', 'Lắp ráp', '2024-04-04 14:00:00'),
('PCX180', 'KHSXX180', 'NV180', 'CL180', 'Kiểm tra', '2024-04-05 14:00:00'),
('PCX181', 'KHSXX181', 'NV181', 'CL181', 'Lắp ráp', '2024-04-06 14:00:00'),
('PCX182', 'KHSXX182', 'NV182', 'CL182', 'Kiểm tra', '2024-04-07 14:00:00'),
('PCX183', 'KHSXX183', 'NV183', 'CL183', 'Lắp ráp', '2024-04-08 14:00:00'),
('PCX184', 'KHSXX184', 'NV184', 'CL184', 'Kiểm tra', '2024-04-09 14:00:00'),
('PCX185', 'KHSXX185', 'NV185', 'CL185', 'Lắp ráp', '2024-04-10 14:00:00'),
('PCX186', 'KHSXX186', 'NV186', 'CL186', 'Kiểm tra', '2024-04-11 14:00:00'),
('PCX187', 'KHSXX187', 'NV187', 'CL187', 'Lắp ráp', '2024-04-12 14:00:00'),
('PCX188', 'KHSXX188', 'NV188', 'CL188', 'Kiểm tra', '2024-04-13 14:00:00'),
('PCX189', 'KHSXX189', 'NV189', 'CL189', 'Lắp ráp', '2024-04-14 14:00:00'),
('PCX190', 'KHSXX190', 'NV190', 'CL190', 'Kiểm tra', '2024-04-15 14:00:00'),
('PCX191', 'KHSXX191', 'NV191', 'CL191', 'Lắp ráp', '2024-04-16 14:00:00'),
('PCX192', 'KHSXX192', 'NV192', 'CL192', 'Kiểm tra', '2024-04-17 14:00:00'),
('PCX193', 'KHSXX193', 'NV193', 'CL193', 'Lắp ráp', '2024-04-18 14:00:00'),
('PCX194', 'KHSXX194', 'NV194', 'CL194', 'Kiểm tra', '2024-04-19 14:00:00'),
('PCX195', 'KHSXX195', 'NV195', 'CL195', 'Lắp ráp', '2024-04-20 14:00:00'),
('PCX196', 'KHSXX196', 'NV196', 'CL196', 'Kiểm tra', '2024-04-21 14:00:00'),
('PCX197', 'KHSXX197', 'NV197', 'CL197', 'Lắp ráp', '2024-04-22 14:00:00'),
('PCX198', 'KHSXX198', 'NV198', 'CL198', 'Kiểm tra', '2024-04-23 14:00:00'),
('PCX199', 'KHSXX199', 'NV199', 'CL199', 'Lắp ráp', '2024-04-24 14:00:00'),
('PCX200', 'KHSXX200', 'NV200', 'CL200', 'Kiểm tra', '2024-04-25 14:00:00'),
('PCX201', 'KHSXX201', 'NV201', 'CL201', 'Lắp ráp', '2024-04-01 14:00:00'),
('PCX202', 'KHSXX202', 'NV202', 'CL202', 'Kiểm tra', '2024-04-02 14:00:00'),
('PCX203', 'KHSXX203', 'NV203', 'CL203', 'Lắp ráp', '2024-04-03 14:00:00'),
('PCX204', 'KHSXX204', 'NV204', 'CL204', 'Kiểm tra', '2024-04-04 14:00:00'),
('PCX205', 'KHSXX205', 'NV205', 'CL205', 'Lắp ráp', '2024-04-05 14:00:00'),
('PCX206', 'KHSXX206', 'NV206', 'CL206', 'Kiểm tra', '2024-04-06 14:00:00'),
('PCX207', 'KHSXX207', 'NV207', 'CL207', 'Lắp ráp', '2024-04-07 14:00:00'),
('PCX208', 'KHSXX208', 'NV208', 'CL208', 'Kiểm tra', '2024-04-08 14:00:00'),
('PCX209', 'KHSXX209', 'NV209', 'CL209', 'Lắp ráp', '2024-04-09 14:00:00'),
('PCX210', 'KHSXX210', 'NV210', 'CL210', 'Kiểm tra', '2024-04-10 14:00:00'),
('PCX211', 'KHSXX211', 'NV211', 'CL211', 'Lắp ráp', '2024-04-11 14:00:00'),
('PCX212', 'KHSXX212', 'NV212', 'CL212', 'Kiểm tra', '2024-04-12 14:00:00'),
('PCX213', 'KHSXX213', 'NV213', 'CL213', 'Lắp ráp', '2024-04-13 14:00:00'),
('PCX214', 'KHSXX214', 'NV214', 'CL214', 'Kiểm tra', '2024-04-14 14:00:00'),
('PCX215', 'KHSXX215', 'NV215', 'CL215', 'Lắp ráp', '2024-04-15 14:00:00'),
('PCX216', 'KHSXX216', 'NV216', 'CL216', 'Kiểm tra', '2024-04-16 14:00:00'),
('PCX217', 'KHSXX217', 'NV217', 'CL217', 'Lắp ráp', '2024-04-17 14:00:00'),
('PCX218', 'KHSXX218', 'NV218', 'CL218', 'Kiểm tra', '2024-04-18 14:00:00'),
('PCX219', 'KHSXX219', 'NV219', 'CL219', 'Lắp ráp', '2024-04-19 14:00:00'),
('PCX220', 'KHSXX220', 'NV220', 'CL220', 'Kiểm tra', '2024-04-20 14:00:00');

INSERT INTO `cham_cong` (`IdChamCong`, `NHANVIEN IdNhanVien`, `ThoiGIanRa`, `ThoiGianVao`, `ViTriVaoLat`, `ViTriVaoLng`, `ViTriVaoAccuracy`, `ViTriRaLat`, `ViTriRaLng`, `ViTriRaAccuracy`, `XUONGTRUONG IdNhanVien`, `IdCaLamViec`, `GhiChu`) VALUES
('CC001', 'NV_SX01', '2024-04-10 17:05:00', '2024-04-10 07:55:00', 10.823100, 106.629700, 5.50, 10.823100, 106.629700, 5.00, 'NV_XU01', 'CL001', 'Đúng giờ'),
('CC002', 'NV_SX02', '2024-04-10 21:10:00', '2024-04-10 12:55:00', 10.833200, 106.620500, 6.20, 10.833200, 106.620500, 5.80, 'NV_XU02', 'CL002', 'Tăng ca 10 phút'),
('CC101', 'NV101', '2024-04-01 17:05:00', '2024-04-01 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL101', 'Chấm công tự động'),
('CC102', 'NV102', '2024-04-02 17:05:00', '2024-04-02 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL102', 'Chấm công tự động'),
('CC103', 'NV103', '2024-04-03 17:05:00', '2024-04-03 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL103', 'Chấm công tự động'),
('CC104', 'NV104', '2024-04-04 17:05:00', '2024-04-04 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL104', 'Chấm công tự động'),
('CC105', 'NV105', '2024-04-05 17:05:00', '2024-04-05 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL105', 'Chấm công tự động'),
('CC106', 'NV106', '2024-04-06 17:05:00', '2024-04-06 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL106', 'Chấm công tự động'),
('CC107', 'NV107', '2024-04-07 17:05:00', '2024-04-07 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL107', 'Chấm công tự động'),
('CC108', 'NV108', '2024-04-08 17:05:00', '2024-04-08 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL108', 'Chấm công tự động'),
('CC109', 'NV109', '2024-04-09 17:05:00', '2024-04-09 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL109', 'Chấm công tự động'),
('CC110', 'NV110', '2024-04-10 17:05:00', '2024-04-10 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL110', 'Chấm công tự động'),
('CC111', 'NV111', '2024-04-11 17:05:00', '2024-04-11 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL111', 'Chấm công tự động'),
('CC112', 'NV112', '2024-04-12 17:05:00', '2024-04-12 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL112', 'Chấm công tự động'),
('CC113', 'NV113', '2024-04-13 17:05:00', '2024-04-13 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL113', 'Chấm công tự động'),
('CC114', 'NV114', '2024-04-14 17:05:00', '2024-04-14 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL114', 'Chấm công tự động'),
('CC115', 'NV115', '2024-04-15 17:05:00', '2024-04-15 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL115', 'Chấm công tự động'),
('CC116', 'NV116', '2024-04-16 17:05:00', '2024-04-16 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL116', 'Chấm công tự động'),
('CC117', 'NV117', '2024-04-17 17:05:00', '2024-04-17 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL117', 'Chấm công tự động'),
('CC118', 'NV118', '2024-04-18 17:05:00', '2024-04-18 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL118', 'Chấm công tự động'),
('CC119', 'NV119', '2024-04-19 17:05:00', '2024-04-19 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL119', 'Chấm công tự động'),
('CC120', 'NV120', '2024-04-20 17:05:00', '2024-04-20 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL120', 'Chấm công tự động'),
('CC121', 'NV121', '2024-04-21 17:05:00', '2024-04-21 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL121', 'Chấm công tự động'),
('CC122', 'NV122', '2024-04-22 17:05:00', '2024-04-22 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL122', 'Chấm công tự động'),
('CC123', 'NV123', '2024-04-23 17:05:00', '2024-04-23 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL123', 'Chấm công tự động'),
('CC124', 'NV124', '2024-04-24 17:05:00', '2024-04-24 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL124', 'Chấm công tự động'),
('CC125', 'NV125', '2024-04-25 17:05:00', '2024-04-25 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL125', 'Chấm công tự động'),
('CC126', 'NV126', '2024-04-26 17:05:00', '2024-04-26 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL126', 'Chấm công tự động'),
('CC127', 'NV127', '2024-04-27 17:05:00', '2024-04-27 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL127', 'Chấm công tự động'),
('CC128', 'NV128', '2024-04-28 17:05:00', '2024-04-28 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL128', 'Chấm công tự động'),
('CC129', 'NV129', '2024-04-01 17:05:00', '2024-04-01 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL129', 'Chấm công tự động'),
('CC130', 'NV130', '2024-04-02 17:05:00', '2024-04-02 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL130', 'Chấm công tự động'),
('CC131', 'NV131', '2024-04-03 17:05:00', '2024-04-03 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL131', 'Chấm công tự động'),
('CC132', 'NV132', '2024-04-04 17:05:00', '2024-04-04 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL132', 'Chấm công tự động'),
('CC133', 'NV133', '2024-04-05 17:05:00', '2024-04-05 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL133', 'Chấm công tự động'),
('CC134', 'NV134', '2024-04-06 17:05:00', '2024-04-06 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL134', 'Chấm công tự động'),
('CC135', 'NV135', '2024-04-07 17:05:00', '2024-04-07 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL135', 'Chấm công tự động'),
('CC136', 'NV136', '2024-04-08 17:05:00', '2024-04-08 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL136', 'Chấm công tự động'),
('CC137', 'NV137', '2024-04-09 17:05:00', '2024-04-09 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL137', 'Chấm công tự động'),
('CC138', 'NV138', '2024-04-10 17:05:00', '2024-04-10 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL138', 'Chấm công tự động'),
('CC139', 'NV139', '2024-04-11 17:05:00', '2024-04-11 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL139', 'Chấm công tự động'),
('CC140', 'NV140', '2024-04-12 17:05:00', '2024-04-12 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL140', 'Chấm công tự động'),
('CC141', 'NV141', '2024-04-13 17:05:00', '2024-04-13 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL141', 'Chấm công tự động'),
('CC142', 'NV142', '2024-04-14 17:05:00', '2024-04-14 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL142', 'Chấm công tự động'),
('CC143', 'NV143', '2024-04-15 17:05:00', '2024-04-15 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL143', 'Chấm công tự động'),
('CC144', 'NV144', '2024-04-16 17:05:00', '2024-04-16 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL144', 'Chấm công tự động'),
('CC145', 'NV145', '2024-04-17 17:05:00', '2024-04-17 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL145', 'Chấm công tự động'),
('CC146', 'NV146', '2024-04-18 17:05:00', '2024-04-18 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL146', 'Chấm công tự động'),
('CC147', 'NV147', '2024-04-19 17:05:00', '2024-04-19 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL147', 'Chấm công tự động'),
('CC148', 'NV148', '2024-04-20 17:05:00', '2024-04-20 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL148', 'Chấm công tự động'),
('CC149', 'NV149', '2024-04-21 17:05:00', '2024-04-21 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL149', 'Chấm công tự động'),
('CC150', 'NV150', '2024-04-22 17:05:00', '2024-04-22 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL150', 'Chấm công tự động'),
('CC151', 'NV151', '2024-04-23 17:05:00', '2024-04-23 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL151', 'Chấm công tự động'),
('CC152', 'NV152', '2024-04-24 17:05:00', '2024-04-24 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL152', 'Chấm công tự động'),
('CC153', 'NV153', '2024-04-25 17:05:00', '2024-04-25 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL153', 'Chấm công tự động'),
('CC154', 'NV154', '2024-04-26 17:05:00', '2024-04-26 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL154', 'Chấm công tự động'),
('CC155', 'NV155', '2024-04-27 17:05:00', '2024-04-27 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL155', 'Chấm công tự động'),
('CC156', 'NV156', '2024-04-28 17:05:00', '2024-04-28 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL156', 'Chấm công tự động'),
('CC157', 'NV157', '2024-04-01 17:05:00', '2024-04-01 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL157', 'Chấm công tự động'),
('CC158', 'NV158', '2024-04-02 17:05:00', '2024-04-02 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL158', 'Chấm công tự động'),
('CC159', 'NV159', '2024-04-03 17:05:00', '2024-04-03 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL159', 'Chấm công tự động'),
('CC160', 'NV160', '2024-04-04 17:05:00', '2024-04-04 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL160', 'Chấm công tự động'),
('CC161', 'NV161', '2024-04-05 17:05:00', '2024-04-05 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL161', 'Chấm công tự động'),
('CC162', 'NV162', '2024-04-06 17:05:00', '2024-04-06 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL162', 'Chấm công tự động'),
('CC163', 'NV163', '2024-04-07 17:05:00', '2024-04-07 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL163', 'Chấm công tự động'),
('CC164', 'NV164', '2024-04-08 17:05:00', '2024-04-08 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL164', 'Chấm công tự động'),
('CC165', 'NV165', '2024-04-09 17:05:00', '2024-04-09 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL165', 'Chấm công tự động'),
('CC166', 'NV166', '2024-04-10 17:05:00', '2024-04-10 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL166', 'Chấm công tự động'),
('CC167', 'NV167', '2024-04-11 17:05:00', '2024-04-11 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL167', 'Chấm công tự động'),
('CC168', 'NV168', '2024-04-12 17:05:00', '2024-04-12 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL168', 'Chấm công tự động'),
('CC169', 'NV169', '2024-04-13 17:05:00', '2024-04-13 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL169', 'Chấm công tự động'),
('CC170', 'NV170', '2024-04-14 17:05:00', '2024-04-14 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL170', 'Chấm công tự động'),
('CC171', 'NV171', '2024-04-15 17:05:00', '2024-04-15 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL171', 'Chấm công tự động'),
('CC172', 'NV172', '2024-04-16 17:05:00', '2024-04-16 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL172', 'Chấm công tự động'),
('CC173', 'NV173', '2024-04-17 17:05:00', '2024-04-17 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL173', 'Chấm công tự động'),
('CC174', 'NV174', '2024-04-18 17:05:00', '2024-04-18 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL174', 'Chấm công tự động'),
('CC175', 'NV175', '2024-04-19 17:05:00', '2024-04-19 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL175', 'Chấm công tự động'),
('CC176', 'NV176', '2024-04-20 17:05:00', '2024-04-20 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL176', 'Chấm công tự động'),
('CC177', 'NV177', '2024-04-21 17:05:00', '2024-04-21 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL177', 'Chấm công tự động'),
('CC178', 'NV178', '2024-04-22 17:05:00', '2024-04-22 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL178', 'Chấm công tự động'),
('CC179', 'NV179', '2024-04-23 17:05:00', '2024-04-23 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL179', 'Chấm công tự động'),
('CC180', 'NV180', '2024-04-24 17:05:00', '2024-04-24 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL180', 'Chấm công tự động'),
('CC181', 'NV181', '2024-04-25 17:05:00', '2024-04-25 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL181', 'Chấm công tự động'),
('CC182', 'NV182', '2024-04-26 17:05:00', '2024-04-26 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL182', 'Chấm công tự động'),
('CC183', 'NV183', '2024-04-27 17:05:00', '2024-04-27 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL183', 'Chấm công tự động'),
('CC184', 'NV184', '2024-04-28 17:05:00', '2024-04-28 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL184', 'Chấm công tự động'),
('CC185', 'NV185', '2024-04-01 17:05:00', '2024-04-01 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL185', 'Chấm công tự động'),
('CC186', 'NV186', '2024-04-02 17:05:00', '2024-04-02 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL186', 'Chấm công tự động'),
('CC187', 'NV187', '2024-04-03 17:05:00', '2024-04-03 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL187', 'Chấm công tự động'),
('CC188', 'NV188', '2024-04-04 17:05:00', '2024-04-04 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL188', 'Chấm công tự động'),
('CC189', 'NV189', '2024-04-05 17:05:00', '2024-04-05 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL189', 'Chấm công tự động'),
('CC190', 'NV190', '2024-04-06 17:05:00', '2024-04-06 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL190', 'Chấm công tự động'),
('CC191', 'NV191', '2024-04-07 17:05:00', '2024-04-07 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL191', 'Chấm công tự động'),
('CC192', 'NV192', '2024-04-08 17:05:00', '2024-04-08 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL192', 'Chấm công tự động'),
('CC193', 'NV193', '2024-04-09 17:05:00', '2024-04-09 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL193', 'Chấm công tự động'),
('CC194', 'NV194', '2024-04-10 17:05:00', '2024-04-10 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL194', 'Chấm công tự động'),
('CC195', 'NV195', '2024-04-11 17:05:00', '2024-04-11 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL195', 'Chấm công tự động'),
('CC196', 'NV196', '2024-04-12 17:05:00', '2024-04-12 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL196', 'Chấm công tự động'),
('CC197', 'NV197', '2024-04-13 17:05:00', '2024-04-13 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL197', 'Chấm công tự động'),
('CC198', 'NV198', '2024-04-14 17:05:00', '2024-04-14 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL198', 'Chấm công tự động'),
('CC199', 'NV199', '2024-04-15 17:05:00', '2024-04-15 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL199', 'Chấm công tự động'),
('CC200', 'NV200', '2024-04-16 17:05:00', '2024-04-16 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL200', 'Chấm công tự động'),
('CC201', 'NV201', '2024-04-17 17:05:00', '2024-04-17 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL201', 'Chấm công tự động'),
('CC202', 'NV202', '2024-04-18 17:05:00', '2024-04-18 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL202', 'Chấm công tự động'),
('CC203', 'NV203', '2024-04-19 17:05:00', '2024-04-19 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL203', 'Chấm công tự động'),
('CC204', 'NV204', '2024-04-20 17:05:00', '2024-04-20 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL204', 'Chấm công tự động'),
('CC205', 'NV205', '2024-04-21 17:05:00', '2024-04-21 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL205', 'Chấm công tự động'),
('CC206', 'NV206', '2024-04-22 17:05:00', '2024-04-22 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL206', 'Chấm công tự động'),
('CC207', 'NV207', '2024-04-23 17:05:00', '2024-04-23 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL207', 'Chấm công tự động'),
('CC208', 'NV208', '2024-04-24 17:05:00', '2024-04-24 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL208', 'Chấm công tự động'),
('CC209', 'NV209', '2024-04-25 17:05:00', '2024-04-25 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL209', 'Chấm công tự động'),
('CC210', 'NV210', '2024-04-26 17:05:00', '2024-04-26 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL210', 'Chấm công tự động'),
('CC211', 'NV211', '2024-04-27 17:05:00', '2024-04-27 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL211', 'Chấm công tự động'),
('CC212', 'NV212', '2024-04-28 17:05:00', '2024-04-28 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL212', 'Chấm công tự động'),
('CC213', 'NV213', '2024-04-01 17:05:00', '2024-04-01 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL213', 'Chấm công tự động'),
('CC214', 'NV214', '2024-04-02 17:05:00', '2024-04-02 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL214', 'Chấm công tự động'),
('CC215', 'NV215', '2024-04-03 17:05:00', '2024-04-03 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL215', 'Chấm công tự động'),
('CC216', 'NV216', '2024-04-04 17:05:00', '2024-04-04 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL216', 'Chấm công tự động'),
('CC217', 'NV217', '2024-04-05 17:05:00', '2024-04-05 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL217', 'Chấm công tự động'),
('CC218', 'NV218', '2024-04-06 17:05:00', '2024-04-06 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL218', 'Chấm công tự động'),
('CC219', 'NV219', '2024-04-07 17:05:00', '2024-04-07 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU01', 'CL219', 'Chấm công tự động'),
('CC220', 'NV220', '2024-04-08 17:05:00', '2024-04-08 07:55:00', 10.8231, 106.6297, 5.50, 10.8231, 106.6297, 5.00, 'NV_XU02', 'CL220', 'Chấm công tự động');

INSERT INTO `bang_luong` (`IdBangLuong`, `KETOAN IdNhanVien2`, `NHAN_VIENIdNhanVien`, `ThangNam`, `LuongCoBan`, `PhuCap`, `DonGiaNgayCong`, `SoNgayCong`, `TongLuongNgayCong`, `Thuong`, `KhauTru`, `TongBaoHiem`, `ThueTNCN`, `TongThuNhap`, `TrangThai`, `NgayLap`, `ChuKy`) VALUES
('BL001', 'NV_KT01', 'NV_SX01', 202404, 7000000, 500000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7190000, 'Đã duyệt', '2024-04-30', NULL),
('BL002', 'NV_KT01', 'NV_SX02', 202404, 7000000, 450000, 320000, 21, 6720000, 250000, 100000, 780000, 130000, 6940000, 'Đã duyệt', '2024-04-30', NULL),
('BL101', 'NV_KT01', 'NV101', 202404, 7000000, 400000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL102', 'NV_KT01', 'NV102', 202405, 7200000, 450000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL103', 'NV_KT01', 'NV103', 202404, 7400000, 500000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL104', 'NV_KT01', 'NV104', 202405, 7600000, 400000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL105', 'NV_KT01', 'NV105', 202404, 7800000, 450000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL106', 'NV_KT01', 'NV106', 202405, 7000000, 500000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL107', 'NV_KT01', 'NV107', 202404, 7200000, 400000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL108', 'NV_KT01', 'NV108', 202405, 7400000, 450000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL109', 'NV_KT01', 'NV109', 202404, 7600000, 500000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL110', 'NV_KT01', 'NV110', 202405, 7800000, 400000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL111', 'NV_KT01', 'NV111', 202404, 7000000, 450000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL112', 'NV_KT01', 'NV112', 202405, 7200000, 500000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL113', 'NV_KT01', 'NV113', 202404, 7400000, 400000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL114', 'NV_KT01', 'NV114', 202405, 7600000, 450000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL115', 'NV_KT01', 'NV115', 202404, 7800000, 500000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL116', 'NV_KT01', 'NV116', 202405, 7000000, 400000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL117', 'NV_KT01', 'NV117', 202404, 7200000, 450000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL118', 'NV_KT01', 'NV118', 202405, 7400000, 500000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL119', 'NV_KT01', 'NV119', 202404, 7600000, 400000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL120', 'NV_KT01', 'NV120', 202405, 7800000, 450000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL),
('BL121', 'NV_KT01', 'NV121', 202404, 7000000, 500000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL122', 'NV_KT01', 'NV122', 202405, 7200000, 400000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL123', 'NV_KT01', 'NV123', 202404, 7400000, 450000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL124', 'NV_KT01', 'NV124', 202405, 7600000, 500000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL125', 'NV_KT01', 'NV125', 202404, 7800000, 400000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL126', 'NV_KT01', 'NV126', 202405, 7000000, 450000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL127', 'NV_KT01', 'NV127', 202404, 7200000, 500000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL128', 'NV_KT01', 'NV128', 202405, 7400000, 400000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL129', 'NV_KT01', 'NV129', 202404, 7600000, 450000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL130', 'NV_KT01', 'NV130', 202405, 7800000, 500000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL131', 'NV_KT01', 'NV131', 202404, 7000000, 400000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL132', 'NV_KT01', 'NV132', 202405, 7200000, 450000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL133', 'NV_KT01', 'NV133', 202404, 7400000, 500000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL134', 'NV_KT01', 'NV134', 202405, 7600000, 400000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL135', 'NV_KT01', 'NV135', 202404, 7800000, 450000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL136', 'NV_KT01', 'NV136', 202405, 7000000, 500000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL137', 'NV_KT01', 'NV137', 202404, 7200000, 400000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL138', 'NV_KT01', 'NV138', 202405, 7400000, 450000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL139', 'NV_KT01', 'NV139', 202404, 7600000, 500000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL140', 'NV_KT01', 'NV140', 202405, 7800000, 400000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL),
('BL141', 'NV_KT01', 'NV141', 202404, 7000000, 450000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL142', 'NV_KT01', 'NV142', 202405, 7200000, 500000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL143', 'NV_KT01', 'NV143', 202404, 7400000, 400000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL144', 'NV_KT01', 'NV144', 202405, 7600000, 450000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL145', 'NV_KT01', 'NV145', 202404, 7800000, 500000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL146', 'NV_KT01', 'NV146', 202405, 7000000, 400000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL147', 'NV_KT01', 'NV147', 202404, 7200000, 450000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL148', 'NV_KT01', 'NV148', 202405, 7400000, 500000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL149', 'NV_KT01', 'NV149', 202404, 7600000, 400000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL150', 'NV_KT01', 'NV150', 202405, 7800000, 450000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL151', 'NV_KT01', 'NV151', 202404, 7000000, 500000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL152', 'NV_KT01', 'NV152', 202405, 7200000, 400000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL153', 'NV_KT01', 'NV153', 202404, 7400000, 450000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL154', 'NV_KT01', 'NV154', 202405, 7600000, 500000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL155', 'NV_KT01', 'NV155', 202404, 7800000, 400000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL156', 'NV_KT01', 'NV156', 202405, 7000000, 450000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL157', 'NV_KT01', 'NV157', 202404, 7200000, 500000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL158', 'NV_KT01', 'NV158', 202405, 7400000, 400000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL159', 'NV_KT01', 'NV159', 202404, 7600000, 450000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL160', 'NV_KT01', 'NV160', 202405, 7800000, 500000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL),
('BL161', 'NV_KT01', 'NV161', 202404, 7000000, 400000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL162', 'NV_KT01', 'NV162', 202405, 7200000, 450000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL163', 'NV_KT01', 'NV163', 202404, 7400000, 500000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL164', 'NV_KT01', 'NV164', 202405, 7600000, 400000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL165', 'NV_KT01', 'NV165', 202404, 7800000, 450000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL166', 'NV_KT01', 'NV166', 202405, 7000000, 500000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL167', 'NV_KT01', 'NV167', 202404, 7200000, 400000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL168', 'NV_KT01', 'NV168', 202405, 7400000, 450000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL169', 'NV_KT01', 'NV169', 202404, 7600000, 500000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL170', 'NV_KT01', 'NV170', 202405, 7800000, 400000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL171', 'NV_KT01', 'NV171', 202404, 7000000, 450000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL172', 'NV_KT01', 'NV172', 202405, 7200000, 500000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL173', 'NV_KT01', 'NV173', 202404, 7400000, 400000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL174', 'NV_KT01', 'NV174', 202405, 7600000, 450000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL175', 'NV_KT01', 'NV175', 202404, 7800000, 500000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL176', 'NV_KT01', 'NV176', 202405, 7000000, 400000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL177', 'NV_KT01', 'NV177', 202404, 7200000, 450000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL178', 'NV_KT01', 'NV178', 202405, 7400000, 500000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL179', 'NV_KT01', 'NV179', 202404, 7600000, 400000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL180', 'NV_KT01', 'NV180', 202405, 7800000, 450000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL),
('BL181', 'NV_KT01', 'NV181', 202404, 7000000, 500000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL182', 'NV_KT01', 'NV182', 202405, 7200000, 400000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL183', 'NV_KT01', 'NV183', 202404, 7400000, 450000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL184', 'NV_KT01', 'NV184', 202405, 7600000, 500000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL185', 'NV_KT01', 'NV185', 202404, 7800000, 400000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL186', 'NV_KT01', 'NV186', 202405, 7000000, 450000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL187', 'NV_KT01', 'NV187', 202404, 7200000, 500000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL188', 'NV_KT01', 'NV188', 202405, 7400000, 400000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL189', 'NV_KT01', 'NV189', 202404, 7600000, 450000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL190', 'NV_KT01', 'NV190', 202405, 7800000, 500000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL191', 'NV_KT01', 'NV191', 202404, 7000000, 400000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL192', 'NV_KT01', 'NV192', 202405, 7200000, 450000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL193', 'NV_KT01', 'NV193', 202404, 7400000, 500000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL194', 'NV_KT01', 'NV194', 202405, 7600000, 400000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL195', 'NV_KT01', 'NV195', 202404, 7800000, 450000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL196', 'NV_KT01', 'NV196', 202405, 7000000, 500000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL197', 'NV_KT01', 'NV197', 202404, 7200000, 400000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL198', 'NV_KT01', 'NV198', 202405, 7400000, 450000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL199', 'NV_KT01', 'NV199', 202404, 7600000, 500000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL200', 'NV_KT01', 'NV200', 202405, 7800000, 400000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL),
('BL201', 'NV_KT01', 'NV201', 202404, 7000000, 450000, 320000, 20, 6400000, 200000, 100000, 800000, 150000, 6350000, 'Đã duyệt', '2024-04-30', NULL),
('BL202', 'NV_KT01', 'NV202', 202405, 7200000, 500000, 320000, 21, 6720000, 250000, 100000, 800000, 150000, 6720000, 'Đã duyệt', '2024-04-30', NULL),
('BL203', 'NV_KT01', 'NV203', 202404, 7400000, 400000, 320000, 22, 7040000, 300000, 100000, 800000, 150000, 7090000, 'Đã duyệt', '2024-04-30', NULL),
('BL204', 'NV_KT01', 'NV204', 202405, 7600000, 450000, 320000, 23, 7360000, 350000, 100000, 800000, 150000, 7460000, 'Đã duyệt', '2024-04-30', NULL),
('BL205', 'NV_KT01', 'NV205', 202404, 7800000, 500000, 320000, 24, 7680000, 200000, 100000, 800000, 150000, 7630000, 'Đã duyệt', '2024-04-30', NULL),
('BL206', 'NV_KT01', 'NV206', 202405, 7000000, 400000, 320000, 20, 6400000, 250000, 100000, 800000, 150000, 6400000, 'Đã duyệt', '2024-04-30', NULL),
('BL207', 'NV_KT01', 'NV207', 202404, 7200000, 450000, 320000, 21, 6720000, 300000, 100000, 800000, 150000, 6770000, 'Đã duyệt', '2024-04-30', NULL),
('BL208', 'NV_KT01', 'NV208', 202405, 7400000, 500000, 320000, 22, 7040000, 350000, 100000, 800000, 150000, 7140000, 'Đã duyệt', '2024-04-30', NULL),
('BL209', 'NV_KT01', 'NV209', 202404, 7600000, 400000, 320000, 23, 7360000, 200000, 100000, 800000, 150000, 7310000, 'Đã duyệt', '2024-04-30', NULL),
('BL210', 'NV_KT01', 'NV210', 202405, 7800000, 450000, 320000, 24, 7680000, 250000, 100000, 800000, 150000, 7680000, 'Đã duyệt', '2024-04-30', NULL),
('BL211', 'NV_KT01', 'NV211', 202404, 7000000, 500000, 320000, 20, 6400000, 300000, 100000, 800000, 150000, 6450000, 'Đã duyệt', '2024-04-30', NULL),
('BL212', 'NV_KT01', 'NV212', 202405, 7200000, 400000, 320000, 21, 6720000, 350000, 100000, 800000, 150000, 6820000, 'Đã duyệt', '2024-04-30', NULL),
('BL213', 'NV_KT01', 'NV213', 202404, 7400000, 450000, 320000, 22, 7040000, 200000, 100000, 800000, 150000, 6990000, 'Đã duyệt', '2024-04-30', NULL),
('BL214', 'NV_KT01', 'NV214', 202405, 7600000, 500000, 320000, 23, 7360000, 250000, 100000, 800000, 150000, 7360000, 'Đã duyệt', '2024-04-30', NULL),
('BL215', 'NV_KT01', 'NV215', 202404, 7800000, 400000, 320000, 24, 7680000, 300000, 100000, 800000, 150000, 7730000, 'Đã duyệt', '2024-04-30', NULL),
('BL216', 'NV_KT01', 'NV216', 202405, 7000000, 450000, 320000, 20, 6400000, 350000, 100000, 800000, 150000, 6500000, 'Đã duyệt', '2024-04-30', NULL),
('BL217', 'NV_KT01', 'NV217', 202404, 7200000, 500000, 320000, 21, 6720000, 200000, 100000, 800000, 150000, 6670000, 'Đã duyệt', '2024-04-30', NULL),
('BL218', 'NV_KT01', 'NV218', 202405, 7400000, 400000, 320000, 22, 7040000, 250000, 100000, 800000, 150000, 7040000, 'Đã duyệt', '2024-04-30', NULL),
('BL219', 'NV_KT01', 'NV219', 202404, 7600000, 450000, 320000, 23, 7360000, 300000, 100000, 800000, 150000, 7410000, 'Đã duyệt', '2024-04-30', NULL),
('BL220', 'NV_KT01', 'NV220', 202405, 7800000, 500000, 320000, 24, 7680000, 350000, 100000, 800000, 150000, 7780000, 'Đã duyệt', '2024-04-30', NULL);

INSERT INTO `phieu` (`IdPhieu`, `NgayLP`, `NgayXN`, `TongTien`, `LoaiPhieu`, `IdKho`, `NHAN_VIENIdNhanVien`, `NHAN_VIENIdNhanVien2`, `LoaiDoiTac`, `DoiTac`, `SoThamChieu`, `LyDo`, `GhiChu`) VALUES
('PH001', '2024-04-07', '2024-04-07', 48000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'YCK001', 'Cấp phát vật tư', 'Xuất cho kế hoạch KHSXX001'),
('PH002', '2024-04-18', '2024-04-18', 125000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'KHSXX002', 'Nhập thành phẩm', 'Nhập lô thành phẩm LO001'),
('PH101', '2024-04-01', '2024-04-01', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF101', 'Cấp phát', 'Phiếu tự động'),
('PH102', '2024-04-02', '2024-04-02', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF102', 'Cấp phát', 'Phiếu tự động'),
('PH103', '2024-04-03', '2024-04-03', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF103', 'Cấp phát', 'Phiếu tự động'),
('PH104', '2024-04-04', '2024-04-04', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF104', 'Cấp phát', 'Phiếu tự động'),
('PH105', '2024-04-05', '2024-04-05', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF105', 'Cấp phát', 'Phiếu tự động'),
('PH106', '2024-04-06', '2024-04-06', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF106', 'Cấp phát', 'Phiếu tự động'),
('PH107', '2024-04-07', '2024-04-07', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF107', 'Cấp phát', 'Phiếu tự động'),
('PH108', '2024-04-08', '2024-04-08', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF108', 'Cấp phát', 'Phiếu tự động'),
('PH109', '2024-04-09', '2024-04-09', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF109', 'Cấp phát', 'Phiếu tự động'),
('PH110', '2024-04-10', '2024-04-10', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF110', 'Cấp phát', 'Phiếu tự động'),
('PH111', '2024-04-11', '2024-04-11', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF111', 'Cấp phát', 'Phiếu tự động'),
('PH112', '2024-04-12', '2024-04-12', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF112', 'Cấp phát', 'Phiếu tự động'),
('PH113', '2024-04-13', '2024-04-13', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF113', 'Cấp phát', 'Phiếu tự động'),
('PH114', '2024-04-14', '2024-04-14', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF114', 'Cấp phát', 'Phiếu tự động'),
('PH115', '2024-04-15', '2024-04-15', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF115', 'Cấp phát', 'Phiếu tự động'),
('PH116', '2024-04-16', '2024-04-16', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF116', 'Cấp phát', 'Phiếu tự động'),
('PH117', '2024-04-17', '2024-04-17', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF117', 'Cấp phát', 'Phiếu tự động'),
('PH118', '2024-04-18', '2024-04-18', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF118', 'Cấp phát', 'Phiếu tự động'),
('PH119', '2024-04-19', '2024-04-19', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF119', 'Cấp phát', 'Phiếu tự động'),
('PH120', '2024-04-20', '2024-04-20', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF120', 'Cấp phát', 'Phiếu tự động'),
('PH121', '2024-04-21', '2024-04-21', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF121', 'Cấp phát', 'Phiếu tự động'),
('PH122', '2024-04-22', '2024-04-22', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF122', 'Cấp phát', 'Phiếu tự động'),
('PH123', '2024-04-23', '2024-04-23', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF123', 'Cấp phát', 'Phiếu tự động'),
('PH124', '2024-04-24', '2024-04-24', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF124', 'Cấp phát', 'Phiếu tự động'),
('PH125', '2024-04-25', '2024-04-25', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF125', 'Cấp phát', 'Phiếu tự động'),
('PH126', '2024-04-26', '2024-04-26', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF126', 'Cấp phát', 'Phiếu tự động'),
('PH127', '2024-04-27', '2024-04-27', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF127', 'Cấp phát', 'Phiếu tự động'),
('PH128', '2024-04-28', '2024-04-28', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF128', 'Cấp phát', 'Phiếu tự động'),
('PH129', '2024-04-01', '2024-04-01', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF129', 'Cấp phát', 'Phiếu tự động'),
('PH130', '2024-04-02', '2024-04-02', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF130', 'Cấp phát', 'Phiếu tự động'),
('PH131', '2024-04-03', '2024-04-03', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF131', 'Cấp phát', 'Phiếu tự động'),
('PH132', '2024-04-04', '2024-04-04', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF132', 'Cấp phát', 'Phiếu tự động'),
('PH133', '2024-04-05', '2024-04-05', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF133', 'Cấp phát', 'Phiếu tự động'),
('PH134', '2024-04-06', '2024-04-06', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF134', 'Cấp phát', 'Phiếu tự động'),
('PH135', '2024-04-07', '2024-04-07', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF135', 'Cấp phát', 'Phiếu tự động'),
('PH136', '2024-04-08', '2024-04-08', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF136', 'Cấp phát', 'Phiếu tự động'),
('PH137', '2024-04-09', '2024-04-09', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF137', 'Cấp phát', 'Phiếu tự động'),
('PH138', '2024-04-10', '2024-04-10', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF138', 'Cấp phát', 'Phiếu tự động'),
('PH139', '2024-04-11', '2024-04-11', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF139', 'Cấp phát', 'Phiếu tự động'),
('PH140', '2024-04-12', '2024-04-12', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF140', 'Cấp phát', 'Phiếu tự động'),
('PH141', '2024-04-13', '2024-04-13', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF141', 'Cấp phát', 'Phiếu tự động'),
('PH142', '2024-04-14', '2024-04-14', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF142', 'Cấp phát', 'Phiếu tự động'),
('PH143', '2024-04-15', '2024-04-15', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF143', 'Cấp phát', 'Phiếu tự động'),
('PH144', '2024-04-16', '2024-04-16', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF144', 'Cấp phát', 'Phiếu tự động'),
('PH145', '2024-04-17', '2024-04-17', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF145', 'Cấp phát', 'Phiếu tự động'),
('PH146', '2024-04-18', '2024-04-18', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF146', 'Cấp phát', 'Phiếu tự động'),
('PH147', '2024-04-19', '2024-04-19', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF147', 'Cấp phát', 'Phiếu tự động'),
('PH148', '2024-04-20', '2024-04-20', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF148', 'Cấp phát', 'Phiếu tự động'),
('PH149', '2024-04-21', '2024-04-21', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF149', 'Cấp phát', 'Phiếu tự động'),
('PH150', '2024-04-22', '2024-04-22', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF150', 'Cấp phát', 'Phiếu tự động'),
('PH151', '2024-04-23', '2024-04-23', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF151', 'Cấp phát', 'Phiếu tự động'),
('PH152', '2024-04-24', '2024-04-24', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF152', 'Cấp phát', 'Phiếu tự động'),
('PH153', '2024-04-25', '2024-04-25', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF153', 'Cấp phát', 'Phiếu tự động'),
('PH154', '2024-04-26', '2024-04-26', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF154', 'Cấp phát', 'Phiếu tự động'),
('PH155', '2024-04-27', '2024-04-27', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF155', 'Cấp phát', 'Phiếu tự động'),
('PH156', '2024-04-28', '2024-04-28', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF156', 'Cấp phát', 'Phiếu tự động'),
('PH157', '2024-04-01', '2024-04-01', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF157', 'Cấp phát', 'Phiếu tự động'),
('PH158', '2024-04-02', '2024-04-02', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF158', 'Cấp phát', 'Phiếu tự động'),
('PH159', '2024-04-03', '2024-04-03', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF159', 'Cấp phát', 'Phiếu tự động'),
('PH160', '2024-04-04', '2024-04-04', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF160', 'Cấp phát', 'Phiếu tự động'),
('PH161', '2024-04-05', '2024-04-05', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF161', 'Cấp phát', 'Phiếu tự động'),
('PH162', '2024-04-06', '2024-04-06', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF162', 'Cấp phát', 'Phiếu tự động'),
('PH163', '2024-04-07', '2024-04-07', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF163', 'Cấp phát', 'Phiếu tự động'),
('PH164', '2024-04-08', '2024-04-08', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF164', 'Cấp phát', 'Phiếu tự động'),
('PH165', '2024-04-09', '2024-04-09', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF165', 'Cấp phát', 'Phiếu tự động'),
('PH166', '2024-04-10', '2024-04-10', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF166', 'Cấp phát', 'Phiếu tự động'),
('PH167', '2024-04-11', '2024-04-11', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF167', 'Cấp phát', 'Phiếu tự động'),
('PH168', '2024-04-12', '2024-04-12', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF168', 'Cấp phát', 'Phiếu tự động'),
('PH169', '2024-04-13', '2024-04-13', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF169', 'Cấp phát', 'Phiếu tự động'),
('PH170', '2024-04-14', '2024-04-14', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF170', 'Cấp phát', 'Phiếu tự động'),
('PH171', '2024-04-15', '2024-04-15', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF171', 'Cấp phát', 'Phiếu tự động'),
('PH172', '2024-04-16', '2024-04-16', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF172', 'Cấp phát', 'Phiếu tự động'),
('PH173', '2024-04-17', '2024-04-17', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF173', 'Cấp phát', 'Phiếu tự động'),
('PH174', '2024-04-18', '2024-04-18', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF174', 'Cấp phát', 'Phiếu tự động'),
('PH175', '2024-04-19', '2024-04-19', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF175', 'Cấp phát', 'Phiếu tự động'),
('PH176', '2024-04-20', '2024-04-20', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF176', 'Cấp phát', 'Phiếu tự động'),
('PH177', '2024-04-21', '2024-04-21', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF177', 'Cấp phát', 'Phiếu tự động'),
('PH178', '2024-04-22', '2024-04-22', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF178', 'Cấp phát', 'Phiếu tự động'),
('PH179', '2024-04-23', '2024-04-23', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF179', 'Cấp phát', 'Phiếu tự động'),
('PH180', '2024-04-24', '2024-04-24', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF180', 'Cấp phát', 'Phiếu tự động'),
('PH181', '2024-04-25', '2024-04-25', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF181', 'Cấp phát', 'Phiếu tự động'),
('PH182', '2024-04-26', '2024-04-26', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF182', 'Cấp phát', 'Phiếu tự động'),
('PH183', '2024-04-27', '2024-04-27', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF183', 'Cấp phát', 'Phiếu tự động'),
('PH184', '2024-04-28', '2024-04-28', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF184', 'Cấp phát', 'Phiếu tự động'),
('PH185', '2024-04-01', '2024-04-01', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF185', 'Cấp phát', 'Phiếu tự động'),
('PH186', '2024-04-02', '2024-04-02', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF186', 'Cấp phát', 'Phiếu tự động'),
('PH187', '2024-04-03', '2024-04-03', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF187', 'Cấp phát', 'Phiếu tự động'),
('PH188', '2024-04-04', '2024-04-04', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF188', 'Cấp phát', 'Phiếu tự động'),
('PH189', '2024-04-05', '2024-04-05', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF189', 'Cấp phát', 'Phiếu tự động'),
('PH190', '2024-04-06', '2024-04-06', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF190', 'Cấp phát', 'Phiếu tự động'),
('PH191', '2024-04-07', '2024-04-07', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF191', 'Cấp phát', 'Phiếu tự động'),
('PH192', '2024-04-08', '2024-04-08', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF192', 'Cấp phát', 'Phiếu tự động'),
('PH193', '2024-04-09', '2024-04-09', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF193', 'Cấp phát', 'Phiếu tự động'),
('PH194', '2024-04-10', '2024-04-10', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF194', 'Cấp phát', 'Phiếu tự động'),
('PH195', '2024-04-11', '2024-04-11', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF195', 'Cấp phát', 'Phiếu tự động'),
('PH196', '2024-04-12', '2024-04-12', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF196', 'Cấp phát', 'Phiếu tự động'),
('PH197', '2024-04-13', '2024-04-13', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF197', 'Cấp phát', 'Phiếu tự động'),
('PH198', '2024-04-14', '2024-04-14', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF198', 'Cấp phát', 'Phiếu tự động'),
('PH199', '2024-04-15', '2024-04-15', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF199', 'Cấp phát', 'Phiếu tự động'),
('PH200', '2024-04-16', '2024-04-16', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF200', 'Cấp phát', 'Phiếu tự động'),
('PH201', '2024-04-17', '2024-04-17', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF201', 'Cấp phát', 'Phiếu tự động'),
('PH202', '2024-04-18', '2024-04-18', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF202', 'Cấp phát', 'Phiếu tự động'),
('PH203', '2024-04-19', '2024-04-19', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF203', 'Cấp phát', 'Phiếu tự động'),
('PH204', '2024-04-20', '2024-04-20', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF204', 'Cấp phát', 'Phiếu tự động'),
('PH205', '2024-04-21', '2024-04-21', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF205', 'Cấp phát', 'Phiếu tự động'),
('PH206', '2024-04-22', '2024-04-22', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF206', 'Cấp phát', 'Phiếu tự động'),
('PH207', '2024-04-23', '2024-04-23', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF207', 'Cấp phát', 'Phiếu tự động'),
('PH208', '2024-04-24', '2024-04-24', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF208', 'Cấp phát', 'Phiếu tự động'),
('PH209', '2024-04-25', '2024-04-25', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF209', 'Cấp phát', 'Phiếu tự động'),
('PH210', '2024-04-26', '2024-04-26', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF210', 'Cấp phát', 'Phiếu tự động'),
('PH211', '2024-04-27', '2024-04-27', 30000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF211', 'Cấp phát', 'Phiếu tự động'),
('PH212', '2024-04-28', '2024-04-28', 31000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF212', 'Cấp phát', 'Phiếu tự động'),
('PH213', '2024-04-01', '2024-04-01', 32000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF213', 'Cấp phát', 'Phiếu tự động'),
('PH214', '2024-04-02', '2024-04-02', 33000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF214', 'Cấp phát', 'Phiếu tự động'),
('PH215', '2024-04-03', '2024-04-03', 34000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF215', 'Cấp phát', 'Phiếu tự động'),
('PH216', '2024-04-04', '2024-04-04', 35000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF216', 'Cấp phát', 'Phiếu tự động'),
('PH217', '2024-04-05', '2024-04-05', 36000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF217', 'Cấp phát', 'Phiếu tự động'),
('PH218', '2024-04-06', '2024-04-06', 37000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF218', 'Cấp phát', 'Phiếu tự động'),
('PH219', '2024-04-07', '2024-04-07', 38000000, 'Phiếu xuất', 'KHO01', 'NV_KNV01', 'NV_KHO01', 'Xưởng', 'Xưởng Lắp Ráp A', 'REF219', 'Cấp phát', 'Phiếu tự động'),
('PH220', '2024-04-08', '2024-04-08', 39000000, 'Phiếu nhập', 'KHO02', 'NV_KNV01', 'NV_KHO02', 'Xưởng', 'Xưởng Hoàn Thiện B', 'REF220', 'Cấp phát', 'Phiếu tự động');

INSERT INTO `ct_phieu` (`IdTTCTPhieu`, `DonViTinh`, `SoLuong`, `ThucNhan`, `IdPhieu`, `IdLo`) VALUES
('CTP001', 'tấm', 120, 118, 'PH001', 'LO004'),
('CTP002', 'chiếc', 850, 850, 'PH001', 'LO005'),
('CTP003', 'bộ', 50, 50, 'PH002', 'LO001'),
('CTP101', 'bộ', 40, 40, 'PH101', 'LO001'),
('CTP102', 'bộ', 41, 41, 'PH102', 'LO002'),
('CTP103', 'bộ', 42, 42, 'PH103', 'LO003'),
('CTP104', 'bộ', 43, 43, 'PH104', 'LO001'),
('CTP105', 'bộ', 44, 44, 'PH105', 'LO002'),
('CTP106', 'bộ', 45, 45, 'PH106', 'LO003'),
('CTP107', 'bộ', 46, 46, 'PH107', 'LO001'),
('CTP108', 'bộ', 47, 47, 'PH108', 'LO002'),
('CTP109', 'bộ', 48, 48, 'PH109', 'LO003'),
('CTP110', 'bộ', 49, 49, 'PH110', 'LO001'),
('CTP111', 'bộ', 50, 50, 'PH111', 'LO002'),
('CTP112', 'bộ', 51, 51, 'PH112', 'LO003'),
('CTP113', 'bộ', 52, 52, 'PH113', 'LO001'),
('CTP114', 'bộ', 53, 53, 'PH114', 'LO002'),
('CTP115', 'bộ', 54, 54, 'PH115', 'LO003'),
('CTP116', 'bộ', 55, 55, 'PH116', 'LO001'),
('CTP117', 'bộ', 56, 56, 'PH117', 'LO002'),
('CTP118', 'bộ', 57, 57, 'PH118', 'LO003'),
('CTP119', 'bộ', 58, 58, 'PH119', 'LO001'),
('CTP120', 'bộ', 59, 59, 'PH120', 'LO002'),
('CTP121', 'bộ', 40, 40, 'PH121', 'LO003'),
('CTP122', 'bộ', 41, 41, 'PH122', 'LO001'),
('CTP123', 'bộ', 42, 42, 'PH123', 'LO002'),
('CTP124', 'bộ', 43, 43, 'PH124', 'LO003'),
('CTP125', 'bộ', 44, 44, 'PH125', 'LO001'),
('CTP126', 'bộ', 45, 45, 'PH126', 'LO002'),
('CTP127', 'bộ', 46, 46, 'PH127', 'LO003'),
('CTP128', 'bộ', 47, 47, 'PH128', 'LO001'),
('CTP129', 'bộ', 48, 48, 'PH129', 'LO002'),
('CTP130', 'bộ', 49, 49, 'PH130', 'LO003'),
('CTP131', 'bộ', 50, 50, 'PH131', 'LO001'),
('CTP132', 'bộ', 51, 51, 'PH132', 'LO002'),
('CTP133', 'bộ', 52, 52, 'PH133', 'LO003'),
('CTP134', 'bộ', 53, 53, 'PH134', 'LO001'),
('CTP135', 'bộ', 54, 54, 'PH135', 'LO002'),
('CTP136', 'bộ', 55, 55, 'PH136', 'LO003'),
('CTP137', 'bộ', 56, 56, 'PH137', 'LO001'),
('CTP138', 'bộ', 57, 57, 'PH138', 'LO002'),
('CTP139', 'bộ', 58, 58, 'PH139', 'LO003'),
('CTP140', 'bộ', 59, 59, 'PH140', 'LO001'),
('CTP141', 'bộ', 40, 40, 'PH141', 'LO002'),
('CTP142', 'bộ', 41, 41, 'PH142', 'LO003'),
('CTP143', 'bộ', 42, 42, 'PH143', 'LO001'),
('CTP144', 'bộ', 43, 43, 'PH144', 'LO002'),
('CTP145', 'bộ', 44, 44, 'PH145', 'LO003'),
('CTP146', 'bộ', 45, 45, 'PH146', 'LO001'),
('CTP147', 'bộ', 46, 46, 'PH147', 'LO002'),
('CTP148', 'bộ', 47, 47, 'PH148', 'LO003'),
('CTP149', 'bộ', 48, 48, 'PH149', 'LO001'),
('CTP150', 'bộ', 49, 49, 'PH150', 'LO002'),
('CTP151', 'bộ', 50, 50, 'PH151', 'LO003'),
('CTP152', 'bộ', 51, 51, 'PH152', 'LO001'),
('CTP153', 'bộ', 52, 52, 'PH153', 'LO002'),
('CTP154', 'bộ', 53, 53, 'PH154', 'LO003'),
('CTP155', 'bộ', 54, 54, 'PH155', 'LO001'),
('CTP156', 'bộ', 55, 55, 'PH156', 'LO002'),
('CTP157', 'bộ', 56, 56, 'PH157', 'LO003'),
('CTP158', 'bộ', 57, 57, 'PH158', 'LO001'),
('CTP159', 'bộ', 58, 58, 'PH159', 'LO002'),
('CTP160', 'bộ', 59, 59, 'PH160', 'LO003'),
('CTP161', 'bộ', 40, 40, 'PH161', 'LO001'),
('CTP162', 'bộ', 41, 41, 'PH162', 'LO002'),
('CTP163', 'bộ', 42, 42, 'PH163', 'LO003'),
('CTP164', 'bộ', 43, 43, 'PH164', 'LO001'),
('CTP165', 'bộ', 44, 44, 'PH165', 'LO002'),
('CTP166', 'bộ', 45, 45, 'PH166', 'LO003'),
('CTP167', 'bộ', 46, 46, 'PH167', 'LO001'),
('CTP168', 'bộ', 47, 47, 'PH168', 'LO002'),
('CTP169', 'bộ', 48, 48, 'PH169', 'LO003'),
('CTP170', 'bộ', 49, 49, 'PH170', 'LO001'),
('CTP171', 'bộ', 50, 50, 'PH171', 'LO002'),
('CTP172', 'bộ', 51, 51, 'PH172', 'LO003'),
('CTP173', 'bộ', 52, 52, 'PH173', 'LO001'),
('CTP174', 'bộ', 53, 53, 'PH174', 'LO002'),
('CTP175', 'bộ', 54, 54, 'PH175', 'LO003'),
('CTP176', 'bộ', 55, 55, 'PH176', 'LO001'),
('CTP177', 'bộ', 56, 56, 'PH177', 'LO002'),
('CTP178', 'bộ', 57, 57, 'PH178', 'LO003'),
('CTP179', 'bộ', 58, 58, 'PH179', 'LO001'),
('CTP180', 'bộ', 59, 59, 'PH180', 'LO002'),
('CTP181', 'bộ', 40, 40, 'PH181', 'LO003'),
('CTP182', 'bộ', 41, 41, 'PH182', 'LO001'),
('CTP183', 'bộ', 42, 42, 'PH183', 'LO002'),
('CTP184', 'bộ', 43, 43, 'PH184', 'LO003'),
('CTP185', 'bộ', 44, 44, 'PH185', 'LO001'),
('CTP186', 'bộ', 45, 45, 'PH186', 'LO002'),
('CTP187', 'bộ', 46, 46, 'PH187', 'LO003'),
('CTP188', 'bộ', 47, 47, 'PH188', 'LO001'),
('CTP189', 'bộ', 48, 48, 'PH189', 'LO002'),
('CTP190', 'bộ', 49, 49, 'PH190', 'LO003'),
('CTP191', 'bộ', 50, 50, 'PH191', 'LO001'),
('CTP192', 'bộ', 51, 51, 'PH192', 'LO002'),
('CTP193', 'bộ', 52, 52, 'PH193', 'LO003'),
('CTP194', 'bộ', 53, 53, 'PH194', 'LO001'),
('CTP195', 'bộ', 54, 54, 'PH195', 'LO002'),
('CTP196', 'bộ', 55, 55, 'PH196', 'LO003'),
('CTP197', 'bộ', 56, 56, 'PH197', 'LO001'),
('CTP198', 'bộ', 57, 57, 'PH198', 'LO002'),
('CTP199', 'bộ', 58, 58, 'PH199', 'LO003'),
('CTP200', 'bộ', 59, 59, 'PH200', 'LO001'),
('CTP201', 'bộ', 40, 40, 'PH201', 'LO002'),
('CTP202', 'bộ', 41, 41, 'PH202', 'LO003'),
('CTP203', 'bộ', 42, 42, 'PH203', 'LO001'),
('CTP204', 'bộ', 43, 43, 'PH204', 'LO002'),
('CTP205', 'bộ', 44, 44, 'PH205', 'LO003'),
('CTP206', 'bộ', 45, 45, 'PH206', 'LO001'),
('CTP207', 'bộ', 46, 46, 'PH207', 'LO002'),
('CTP208', 'bộ', 47, 47, 'PH208', 'LO003'),
('CTP209', 'bộ', 48, 48, 'PH209', 'LO001'),
('CTP210', 'bộ', 49, 49, 'PH210', 'LO002'),
('CTP211', 'bộ', 50, 50, 'PH211', 'LO003'),
('CTP212', 'bộ', 51, 51, 'PH212', 'LO001'),
('CTP213', 'bộ', 52, 52, 'PH213', 'LO002'),
('CTP214', 'bộ', 53, 53, 'PH214', 'LO003'),
('CTP215', 'bộ', 54, 54, 'PH215', 'LO001'),
('CTP216', 'bộ', 55, 55, 'PH216', 'LO002'),
('CTP217', 'bộ', 56, 56, 'PH217', 'LO003'),
('CTP218', 'bộ', 57, 57, 'PH218', 'LO001'),
('CTP219', 'bộ', 58, 58, 'PH219', 'LO002'),
('CTP220', 'bộ', 59, 59, 'PH220', 'LO003');

INSERT INTO `hoa_don` (`IdHoaDon`, `NgayLap`, `TrangThai`, `LoaiHD`, `IdDonHang`) VALUES
('HD001', '2024-04-25', 'Đã xuất', 'Hóa đơn bán hàng', 'DH001'),
('HD101', '2024-04-01', 'Đã xuất', 'Hóa đơn bán hàng', 'DH101'),
('HD102', '2024-04-02', 'Đã xuất', 'Hóa đơn bán hàng', 'DH102'),
('HD103', '2024-04-03', 'Đã xuất', 'Hóa đơn bán hàng', 'DH103'),
('HD104', '2024-04-04', 'Đã xuất', 'Hóa đơn bán hàng', 'DH104'),
('HD105', '2024-04-05', 'Đã xuất', 'Hóa đơn bán hàng', 'DH105'),
('HD106', '2024-04-06', 'Đã xuất', 'Hóa đơn bán hàng', 'DH106'),
('HD107', '2024-04-07', 'Đã xuất', 'Hóa đơn bán hàng', 'DH107'),
('HD108', '2024-04-08', 'Đã xuất', 'Hóa đơn bán hàng', 'DH108'),
('HD109', '2024-04-09', 'Đã xuất', 'Hóa đơn bán hàng', 'DH109'),
('HD110', '2024-04-10', 'Đã xuất', 'Hóa đơn bán hàng', 'DH110'),
('HD111', '2024-04-11', 'Đã xuất', 'Hóa đơn bán hàng', 'DH111'),
('HD112', '2024-04-12', 'Đã xuất', 'Hóa đơn bán hàng', 'DH112'),
('HD113', '2024-04-13', 'Đã xuất', 'Hóa đơn bán hàng', 'DH113'),
('HD114', '2024-04-14', 'Đã xuất', 'Hóa đơn bán hàng', 'DH114'),
('HD115', '2024-04-15', 'Đã xuất', 'Hóa đơn bán hàng', 'DH115'),
('HD116', '2024-04-16', 'Đã xuất', 'Hóa đơn bán hàng', 'DH116'),
('HD117', '2024-04-17', 'Đã xuất', 'Hóa đơn bán hàng', 'DH117'),
('HD118', '2024-04-18', 'Đã xuất', 'Hóa đơn bán hàng', 'DH118'),
('HD119', '2024-04-19', 'Đã xuất', 'Hóa đơn bán hàng', 'DH119'),
('HD120', '2024-04-20', 'Đã xuất', 'Hóa đơn bán hàng', 'DH120'),
('HD121', '2024-04-21', 'Đã xuất', 'Hóa đơn bán hàng', 'DH121'),
('HD122', '2024-04-22', 'Đã xuất', 'Hóa đơn bán hàng', 'DH122'),
('HD123', '2024-04-23', 'Đã xuất', 'Hóa đơn bán hàng', 'DH123'),
('HD124', '2024-04-24', 'Đã xuất', 'Hóa đơn bán hàng', 'DH124'),
('HD125', '2024-04-25', 'Đã xuất', 'Hóa đơn bán hàng', 'DH125'),
('HD126', '2024-04-26', 'Đã xuất', 'Hóa đơn bán hàng', 'DH126'),
('HD127', '2024-04-27', 'Đã xuất', 'Hóa đơn bán hàng', 'DH127'),
('HD128', '2024-04-28', 'Đã xuất', 'Hóa đơn bán hàng', 'DH128'),
('HD129', '2024-04-01', 'Đã xuất', 'Hóa đơn bán hàng', 'DH129'),
('HD130', '2024-04-02', 'Đã xuất', 'Hóa đơn bán hàng', 'DH130'),
('HD131', '2024-04-03', 'Đã xuất', 'Hóa đơn bán hàng', 'DH131'),
('HD132', '2024-04-04', 'Đã xuất', 'Hóa đơn bán hàng', 'DH132'),
('HD133', '2024-04-05', 'Đã xuất', 'Hóa đơn bán hàng', 'DH133'),
('HD134', '2024-04-06', 'Đã xuất', 'Hóa đơn bán hàng', 'DH134'),
('HD135', '2024-04-07', 'Đã xuất', 'Hóa đơn bán hàng', 'DH135'),
('HD136', '2024-04-08', 'Đã xuất', 'Hóa đơn bán hàng', 'DH136'),
('HD137', '2024-04-09', 'Đã xuất', 'Hóa đơn bán hàng', 'DH137'),
('HD138', '2024-04-10', 'Đã xuất', 'Hóa đơn bán hàng', 'DH138'),
('HD139', '2024-04-11', 'Đã xuất', 'Hóa đơn bán hàng', 'DH139'),
('HD140', '2024-04-12', 'Đã xuất', 'Hóa đơn bán hàng', 'DH140'),
('HD141', '2024-04-13', 'Đã xuất', 'Hóa đơn bán hàng', 'DH141'),
('HD142', '2024-04-14', 'Đã xuất', 'Hóa đơn bán hàng', 'DH142'),
('HD143', '2024-04-15', 'Đã xuất', 'Hóa đơn bán hàng', 'DH143'),
('HD144', '2024-04-16', 'Đã xuất', 'Hóa đơn bán hàng', 'DH144'),
('HD145', '2024-04-17', 'Đã xuất', 'Hóa đơn bán hàng', 'DH145'),
('HD146', '2024-04-18', 'Đã xuất', 'Hóa đơn bán hàng', 'DH146'),
('HD147', '2024-04-19', 'Đã xuất', 'Hóa đơn bán hàng', 'DH147'),
('HD148', '2024-04-20', 'Đã xuất', 'Hóa đơn bán hàng', 'DH148'),
('HD149', '2024-04-21', 'Đã xuất', 'Hóa đơn bán hàng', 'DH149'),
('HD150', '2024-04-22', 'Đã xuất', 'Hóa đơn bán hàng', 'DH150'),
('HD151', '2024-04-23', 'Đã xuất', 'Hóa đơn bán hàng', 'DH151'),
('HD152', '2024-04-24', 'Đã xuất', 'Hóa đơn bán hàng', 'DH152'),
('HD153', '2024-04-25', 'Đã xuất', 'Hóa đơn bán hàng', 'DH153'),
('HD154', '2024-04-26', 'Đã xuất', 'Hóa đơn bán hàng', 'DH154'),
('HD155', '2024-04-27', 'Đã xuất', 'Hóa đơn bán hàng', 'DH155'),
('HD156', '2024-04-28', 'Đã xuất', 'Hóa đơn bán hàng', 'DH156'),
('HD157', '2024-04-01', 'Đã xuất', 'Hóa đơn bán hàng', 'DH157'),
('HD158', '2024-04-02', 'Đã xuất', 'Hóa đơn bán hàng', 'DH158'),
('HD159', '2024-04-03', 'Đã xuất', 'Hóa đơn bán hàng', 'DH159'),
('HD160', '2024-04-04', 'Đã xuất', 'Hóa đơn bán hàng', 'DH160'),
('HD161', '2024-04-05', 'Đã xuất', 'Hóa đơn bán hàng', 'DH161'),
('HD162', '2024-04-06', 'Đã xuất', 'Hóa đơn bán hàng', 'DH162'),
('HD163', '2024-04-07', 'Đã xuất', 'Hóa đơn bán hàng', 'DH163'),
('HD164', '2024-04-08', 'Đã xuất', 'Hóa đơn bán hàng', 'DH164'),
('HD165', '2024-04-09', 'Đã xuất', 'Hóa đơn bán hàng', 'DH165'),
('HD166', '2024-04-10', 'Đã xuất', 'Hóa đơn bán hàng', 'DH166'),
('HD167', '2024-04-11', 'Đã xuất', 'Hóa đơn bán hàng', 'DH167'),
('HD168', '2024-04-12', 'Đã xuất', 'Hóa đơn bán hàng', 'DH168'),
('HD169', '2024-04-13', 'Đã xuất', 'Hóa đơn bán hàng', 'DH169'),
('HD170', '2024-04-14', 'Đã xuất', 'Hóa đơn bán hàng', 'DH170'),
('HD171', '2024-04-15', 'Đã xuất', 'Hóa đơn bán hàng', 'DH171'),
('HD172', '2024-04-16', 'Đã xuất', 'Hóa đơn bán hàng', 'DH172'),
('HD173', '2024-04-17', 'Đã xuất', 'Hóa đơn bán hàng', 'DH173'),
('HD174', '2024-04-18', 'Đã xuất', 'Hóa đơn bán hàng', 'DH174'),
('HD175', '2024-04-19', 'Đã xuất', 'Hóa đơn bán hàng', 'DH175'),
('HD176', '2024-04-20', 'Đã xuất', 'Hóa đơn bán hàng', 'DH176'),
('HD177', '2024-04-21', 'Đã xuất', 'Hóa đơn bán hàng', 'DH177'),
('HD178', '2024-04-22', 'Đã xuất', 'Hóa đơn bán hàng', 'DH178'),
('HD179', '2024-04-23', 'Đã xuất', 'Hóa đơn bán hàng', 'DH179'),
('HD180', '2024-04-24', 'Đã xuất', 'Hóa đơn bán hàng', 'DH180'),
('HD181', '2024-04-25', 'Đã xuất', 'Hóa đơn bán hàng', 'DH181'),
('HD182', '2024-04-26', 'Đã xuất', 'Hóa đơn bán hàng', 'DH182'),
('HD183', '2024-04-27', 'Đã xuất', 'Hóa đơn bán hàng', 'DH183'),
('HD184', '2024-04-28', 'Đã xuất', 'Hóa đơn bán hàng', 'DH184'),
('HD185', '2024-04-01', 'Đã xuất', 'Hóa đơn bán hàng', 'DH185'),
('HD186', '2024-04-02', 'Đã xuất', 'Hóa đơn bán hàng', 'DH186'),
('HD187', '2024-04-03', 'Đã xuất', 'Hóa đơn bán hàng', 'DH187'),
('HD188', '2024-04-04', 'Đã xuất', 'Hóa đơn bán hàng', 'DH188'),
('HD189', '2024-04-05', 'Đã xuất', 'Hóa đơn bán hàng', 'DH189'),
('HD190', '2024-04-06', 'Đã xuất', 'Hóa đơn bán hàng', 'DH190'),
('HD191', '2024-04-07', 'Đã xuất', 'Hóa đơn bán hàng', 'DH191'),
('HD192', '2024-04-08', 'Đã xuất', 'Hóa đơn bán hàng', 'DH192'),
('HD193', '2024-04-09', 'Đã xuất', 'Hóa đơn bán hàng', 'DH193'),
('HD194', '2024-04-10', 'Đã xuất', 'Hóa đơn bán hàng', 'DH194'),
('HD195', '2024-04-11', 'Đã xuất', 'Hóa đơn bán hàng', 'DH195'),
('HD196', '2024-04-12', 'Đã xuất', 'Hóa đơn bán hàng', 'DH196'),
('HD197', '2024-04-13', 'Đã xuất', 'Hóa đơn bán hàng', 'DH197'),
('HD198', '2024-04-14', 'Đã xuất', 'Hóa đơn bán hàng', 'DH198'),
('HD199', '2024-04-15', 'Đã xuất', 'Hóa đơn bán hàng', 'DH199'),
('HD200', '2024-04-16', 'Đã xuất', 'Hóa đơn bán hàng', 'DH200'),
('HD201', '2024-04-17', 'Đã xuất', 'Hóa đơn bán hàng', 'DH201'),
('HD202', '2024-04-18', 'Đã xuất', 'Hóa đơn bán hàng', 'DH202'),
('HD203', '2024-04-19', 'Đã xuất', 'Hóa đơn bán hàng', 'DH203'),
('HD204', '2024-04-20', 'Đã xuất', 'Hóa đơn bán hàng', 'DH204'),
('HD205', '2024-04-21', 'Đã xuất', 'Hóa đơn bán hàng', 'DH205'),
('HD206', '2024-04-22', 'Đã xuất', 'Hóa đơn bán hàng', 'DH206'),
('HD207', '2024-04-23', 'Đã xuất', 'Hóa đơn bán hàng', 'DH207'),
('HD208', '2024-04-24', 'Đã xuất', 'Hóa đơn bán hàng', 'DH208'),
('HD209', '2024-04-25', 'Đã xuất', 'Hóa đơn bán hàng', 'DH209'),
('HD210', '2024-04-26', 'Đã xuất', 'Hóa đơn bán hàng', 'DH210'),
('HD211', '2024-04-27', 'Đã xuất', 'Hóa đơn bán hàng', 'DH211'),
('HD212', '2024-04-28', 'Đã xuất', 'Hóa đơn bán hàng', 'DH212'),
('HD213', '2024-04-01', 'Đã xuất', 'Hóa đơn bán hàng', 'DH213'),
('HD214', '2024-04-02', 'Đã xuất', 'Hóa đơn bán hàng', 'DH214'),
('HD215', '2024-04-03', 'Đã xuất', 'Hóa đơn bán hàng', 'DH215'),
('HD216', '2024-04-04', 'Đã xuất', 'Hóa đơn bán hàng', 'DH216'),
('HD217', '2024-04-05', 'Đã xuất', 'Hóa đơn bán hàng', 'DH217'),
('HD218', '2024-04-06', 'Đã xuất', 'Hóa đơn bán hàng', 'DH218'),
('HD219', '2024-04-07', 'Đã xuất', 'Hóa đơn bán hàng', 'DH219'),
('HD220', '2024-04-08', 'Đã xuất', 'Hóa đơn bán hàng', 'DH220');

INSERT INTO `ct_hoa_don` (`IdCTHoaDon`, `SoLuong`, `ThueVAT`, `TongTien`, `PhuongThucTT`, `IdHoaDon`, `IdLo`) VALUES
('CTHD001', 100, 10, 250000000, 'Chuyển khoản', 'HD001', 'LO001'),
('CTHD101', 50, 10, 125000000, 'Chuyển khoản', 'HD101', 'LO001'),
('CTHD102', 51, 10, 127500000, 'Chuyển khoản', 'HD102', 'LO002'),
('CTHD103', 52, 10, 130000000, 'Chuyển khoản', 'HD103', 'LO003'),
('CTHD104', 53, 10, 132500000, 'Chuyển khoản', 'HD104', 'LO001'),
('CTHD105', 54, 10, 135000000, 'Chuyển khoản', 'HD105', 'LO002'),
('CTHD106', 55, 10, 137500000, 'Chuyển khoản', 'HD106', 'LO003'),
('CTHD107', 56, 10, 140000000, 'Chuyển khoản', 'HD107', 'LO001'),
('CTHD108', 57, 10, 142500000, 'Chuyển khoản', 'HD108', 'LO002'),
('CTHD109', 58, 10, 145000000, 'Chuyển khoản', 'HD109', 'LO003'),
('CTHD110', 59, 10, 147500000, 'Chuyển khoản', 'HD110', 'LO001'),
('CTHD111', 60, 10, 150000000, 'Chuyển khoản', 'HD111', 'LO002'),
('CTHD112', 61, 10, 152500000, 'Chuyển khoản', 'HD112', 'LO003'),
('CTHD113', 62, 10, 155000000, 'Chuyển khoản', 'HD113', 'LO001'),
('CTHD114', 63, 10, 157500000, 'Chuyển khoản', 'HD114', 'LO002'),
('CTHD115', 64, 10, 160000000, 'Chuyển khoản', 'HD115', 'LO003'),
('CTHD116', 65, 10, 162500000, 'Chuyển khoản', 'HD116', 'LO001'),
('CTHD117', 66, 10, 165000000, 'Chuyển khoản', 'HD117', 'LO002'),
('CTHD118', 67, 10, 167500000, 'Chuyển khoản', 'HD118', 'LO003'),
('CTHD119', 68, 10, 170000000, 'Chuyển khoản', 'HD119', 'LO001'),
('CTHD120', 69, 10, 172500000, 'Chuyển khoản', 'HD120', 'LO002'),
('CTHD121', 50, 10, 125000000, 'Chuyển khoản', 'HD121', 'LO003'),
('CTHD122', 51, 10, 127500000, 'Chuyển khoản', 'HD122', 'LO001'),
('CTHD123', 52, 10, 130000000, 'Chuyển khoản', 'HD123', 'LO002'),
('CTHD124', 53, 10, 132500000, 'Chuyển khoản', 'HD124', 'LO003'),
('CTHD125', 54, 10, 135000000, 'Chuyển khoản', 'HD125', 'LO001'),
('CTHD126', 55, 10, 137500000, 'Chuyển khoản', 'HD126', 'LO002'),
('CTHD127', 56, 10, 140000000, 'Chuyển khoản', 'HD127', 'LO003'),
('CTHD128', 57, 10, 142500000, 'Chuyển khoản', 'HD128', 'LO001'),
('CTHD129', 58, 10, 145000000, 'Chuyển khoản', 'HD129', 'LO002'),
('CTHD130', 59, 10, 147500000, 'Chuyển khoản', 'HD130', 'LO003'),
('CTHD131', 60, 10, 150000000, 'Chuyển khoản', 'HD131', 'LO001'),
('CTHD132', 61, 10, 152500000, 'Chuyển khoản', 'HD132', 'LO002'),
('CTHD133', 62, 10, 155000000, 'Chuyển khoản', 'HD133', 'LO003'),
('CTHD134', 63, 10, 157500000, 'Chuyển khoản', 'HD134', 'LO001'),
('CTHD135', 64, 10, 160000000, 'Chuyển khoản', 'HD135', 'LO002'),
('CTHD136', 65, 10, 162500000, 'Chuyển khoản', 'HD136', 'LO003'),
('CTHD137', 66, 10, 165000000, 'Chuyển khoản', 'HD137', 'LO001'),
('CTHD138', 67, 10, 167500000, 'Chuyển khoản', 'HD138', 'LO002'),
('CTHD139', 68, 10, 170000000, 'Chuyển khoản', 'HD139', 'LO003'),
('CTHD140', 69, 10, 172500000, 'Chuyển khoản', 'HD140', 'LO001'),
('CTHD141', 50, 10, 125000000, 'Chuyển khoản', 'HD141', 'LO002'),
('CTHD142', 51, 10, 127500000, 'Chuyển khoản', 'HD142', 'LO003'),
('CTHD143', 52, 10, 130000000, 'Chuyển khoản', 'HD143', 'LO001'),
('CTHD144', 53, 10, 132500000, 'Chuyển khoản', 'HD144', 'LO002'),
('CTHD145', 54, 10, 135000000, 'Chuyển khoản', 'HD145', 'LO003'),
('CTHD146', 55, 10, 137500000, 'Chuyển khoản', 'HD146', 'LO001'),
('CTHD147', 56, 10, 140000000, 'Chuyển khoản', 'HD147', 'LO002'),
('CTHD148', 57, 10, 142500000, 'Chuyển khoản', 'HD148', 'LO003'),
('CTHD149', 58, 10, 145000000, 'Chuyển khoản', 'HD149', 'LO001'),
('CTHD150', 59, 10, 147500000, 'Chuyển khoản', 'HD150', 'LO002'),
('CTHD151', 60, 10, 150000000, 'Chuyển khoản', 'HD151', 'LO003'),
('CTHD152', 61, 10, 152500000, 'Chuyển khoản', 'HD152', 'LO001'),
('CTHD153', 62, 10, 155000000, 'Chuyển khoản', 'HD153', 'LO002'),
('CTHD154', 63, 10, 157500000, 'Chuyển khoản', 'HD154', 'LO003'),
('CTHD155', 64, 10, 160000000, 'Chuyển khoản', 'HD155', 'LO001'),
('CTHD156', 65, 10, 162500000, 'Chuyển khoản', 'HD156', 'LO002'),
('CTHD157', 66, 10, 165000000, 'Chuyển khoản', 'HD157', 'LO003'),
('CTHD158', 67, 10, 167500000, 'Chuyển khoản', 'HD158', 'LO001'),
('CTHD159', 68, 10, 170000000, 'Chuyển khoản', 'HD159', 'LO002'),
('CTHD160', 69, 10, 172500000, 'Chuyển khoản', 'HD160', 'LO003'),
('CTHD161', 50, 10, 125000000, 'Chuyển khoản', 'HD161', 'LO001'),
('CTHD162', 51, 10, 127500000, 'Chuyển khoản', 'HD162', 'LO002'),
('CTHD163', 52, 10, 130000000, 'Chuyển khoản', 'HD163', 'LO003'),
('CTHD164', 53, 10, 132500000, 'Chuyển khoản', 'HD164', 'LO001'),
('CTHD165', 54, 10, 135000000, 'Chuyển khoản', 'HD165', 'LO002'),
('CTHD166', 55, 10, 137500000, 'Chuyển khoản', 'HD166', 'LO003'),
('CTHD167', 56, 10, 140000000, 'Chuyển khoản', 'HD167', 'LO001'),
('CTHD168', 57, 10, 142500000, 'Chuyển khoản', 'HD168', 'LO002'),
('CTHD169', 58, 10, 145000000, 'Chuyển khoản', 'HD169', 'LO003'),
('CTHD170', 59, 10, 147500000, 'Chuyển khoản', 'HD170', 'LO001'),
('CTHD171', 60, 10, 150000000, 'Chuyển khoản', 'HD171', 'LO002'),
('CTHD172', 61, 10, 152500000, 'Chuyển khoản', 'HD172', 'LO003'),
('CTHD173', 62, 10, 155000000, 'Chuyển khoản', 'HD173', 'LO001'),
('CTHD174', 63, 10, 157500000, 'Chuyển khoản', 'HD174', 'LO002'),
('CTHD175', 64, 10, 160000000, 'Chuyển khoản', 'HD175', 'LO003'),
('CTHD176', 65, 10, 162500000, 'Chuyển khoản', 'HD176', 'LO001'),
('CTHD177', 66, 10, 165000000, 'Chuyển khoản', 'HD177', 'LO002'),
('CTHD178', 67, 10, 167500000, 'Chuyển khoản', 'HD178', 'LO003'),
('CTHD179', 68, 10, 170000000, 'Chuyển khoản', 'HD179', 'LO001'),
('CTHD180', 69, 10, 172500000, 'Chuyển khoản', 'HD180', 'LO002'),
('CTHD181', 50, 10, 125000000, 'Chuyển khoản', 'HD181', 'LO003'),
('CTHD182', 51, 10, 127500000, 'Chuyển khoản', 'HD182', 'LO001'),
('CTHD183', 52, 10, 130000000, 'Chuyển khoản', 'HD183', 'LO002'),
('CTHD184', 53, 10, 132500000, 'Chuyển khoản', 'HD184', 'LO003'),
('CTHD185', 54, 10, 135000000, 'Chuyển khoản', 'HD185', 'LO001'),
('CTHD186', 55, 10, 137500000, 'Chuyển khoản', 'HD186', 'LO002'),
('CTHD187', 56, 10, 140000000, 'Chuyển khoản', 'HD187', 'LO003'),
('CTHD188', 57, 10, 142500000, 'Chuyển khoản', 'HD188', 'LO001'),
('CTHD189', 58, 10, 145000000, 'Chuyển khoản', 'HD189', 'LO002'),
('CTHD190', 59, 10, 147500000, 'Chuyển khoản', 'HD190', 'LO003'),
('CTHD191', 60, 10, 150000000, 'Chuyển khoản', 'HD191', 'LO001'),
('CTHD192', 61, 10, 152500000, 'Chuyển khoản', 'HD192', 'LO002'),
('CTHD193', 62, 10, 155000000, 'Chuyển khoản', 'HD193', 'LO003'),
('CTHD194', 63, 10, 157500000, 'Chuyển khoản', 'HD194', 'LO001'),
('CTHD195', 64, 10, 160000000, 'Chuyển khoản', 'HD195', 'LO002'),
('CTHD196', 65, 10, 162500000, 'Chuyển khoản', 'HD196', 'LO003'),
('CTHD197', 66, 10, 165000000, 'Chuyển khoản', 'HD197', 'LO001'),
('CTHD198', 67, 10, 167500000, 'Chuyển khoản', 'HD198', 'LO002'),
('CTHD199', 68, 10, 170000000, 'Chuyển khoản', 'HD199', 'LO003'),
('CTHD200', 69, 10, 172500000, 'Chuyển khoản', 'HD200', 'LO001'),
('CTHD201', 50, 10, 125000000, 'Chuyển khoản', 'HD201', 'LO002'),
('CTHD202', 51, 10, 127500000, 'Chuyển khoản', 'HD202', 'LO003'),
('CTHD203', 52, 10, 130000000, 'Chuyển khoản', 'HD203', 'LO001'),
('CTHD204', 53, 10, 132500000, 'Chuyển khoản', 'HD204', 'LO002'),
('CTHD205', 54, 10, 135000000, 'Chuyển khoản', 'HD205', 'LO003'),
('CTHD206', 55, 10, 137500000, 'Chuyển khoản', 'HD206', 'LO001'),
('CTHD207', 56, 10, 140000000, 'Chuyển khoản', 'HD207', 'LO002'),
('CTHD208', 57, 10, 142500000, 'Chuyển khoản', 'HD208', 'LO003'),
('CTHD209', 58, 10, 145000000, 'Chuyển khoản', 'HD209', 'LO001'),
('CTHD210', 59, 10, 147500000, 'Chuyển khoản', 'HD210', 'LO002'),
('CTHD211', 60, 10, 150000000, 'Chuyển khoản', 'HD211', 'LO003'),
('CTHD212', 61, 10, 152500000, 'Chuyển khoản', 'HD212', 'LO001'),
('CTHD213', 62, 10, 155000000, 'Chuyển khoản', 'HD213', 'LO002'),
('CTHD214', 63, 10, 157500000, 'Chuyển khoản', 'HD214', 'LO003'),
('CTHD215', 64, 10, 160000000, 'Chuyển khoản', 'HD215', 'LO001'),
('CTHD216', 65, 10, 162500000, 'Chuyển khoản', 'HD216', 'LO002'),
('CTHD217', 66, 10, 165000000, 'Chuyển khoản', 'HD217', 'LO003'),
('CTHD218', 67, 10, 167500000, 'Chuyển khoản', 'HD218', 'LO001'),
('CTHD219', 68, 10, 170000000, 'Chuyển khoản', 'HD219', 'LO002'),
('CTHD220', 69, 10, 172500000, 'Chuyển khoản', 'HD220', 'LO003');

INSERT INTO `hoat_dong_he_thong` (`IdHoatDong`, `HanhDong`, `ThoiGian`, `IdNguoiDung`) VALUES
('HDHT001', 'Tạo kế hoạch sản xuất KHSX001', '2024-04-05 08:05:00', 'ND_XU01'),
('HDHT002', 'Duyệt yêu cầu xuất kho YCK001', '2024-04-07 10:00:00', 'ND_KHO01'),
('HDHT101', 'Cập nhật kế hoạch 101', '2024-04-01 09:00:00', 'ND101'),
('HDHT102', 'Cập nhật kế hoạch 102', '2024-04-02 09:00:00', 'ND102'),
('HDHT103', 'Cập nhật kế hoạch 103', '2024-04-03 09:00:00', 'ND103'),
('HDHT104', 'Cập nhật kế hoạch 104', '2024-04-04 09:00:00', 'ND104'),
('HDHT105', 'Cập nhật kế hoạch 105', '2024-04-05 09:00:00', 'ND105'),
('HDHT106', 'Cập nhật kế hoạch 106', '2024-04-06 09:00:00', 'ND106'),
('HDHT107', 'Cập nhật kế hoạch 107', '2024-04-07 09:00:00', 'ND107'),
('HDHT108', 'Cập nhật kế hoạch 108', '2024-04-08 09:00:00', 'ND108'),
('HDHT109', 'Cập nhật kế hoạch 109', '2024-04-09 09:00:00', 'ND109'),
('HDHT110', 'Cập nhật kế hoạch 110', '2024-04-10 09:00:00', 'ND110'),
('HDHT111', 'Cập nhật kế hoạch 111', '2024-04-11 09:00:00', 'ND111'),
('HDHT112', 'Cập nhật kế hoạch 112', '2024-04-12 09:00:00', 'ND112'),
('HDHT113', 'Cập nhật kế hoạch 113', '2024-04-13 09:00:00', 'ND113'),
('HDHT114', 'Cập nhật kế hoạch 114', '2024-04-14 09:00:00', 'ND114'),
('HDHT115', 'Cập nhật kế hoạch 115', '2024-04-15 09:00:00', 'ND115'),
('HDHT116', 'Cập nhật kế hoạch 116', '2024-04-16 09:00:00', 'ND116'),
('HDHT117', 'Cập nhật kế hoạch 117', '2024-04-17 09:00:00', 'ND117'),
('HDHT118', 'Cập nhật kế hoạch 118', '2024-04-18 09:00:00', 'ND118'),
('HDHT119', 'Cập nhật kế hoạch 119', '2024-04-19 09:00:00', 'ND119'),
('HDHT120', 'Cập nhật kế hoạch 120', '2024-04-20 09:00:00', 'ND120'),
('HDHT121', 'Cập nhật kế hoạch 121', '2024-04-21 09:00:00', 'ND121'),
('HDHT122', 'Cập nhật kế hoạch 122', '2024-04-22 09:00:00', 'ND122'),
('HDHT123', 'Cập nhật kế hoạch 123', '2024-04-23 09:00:00', 'ND123'),
('HDHT124', 'Cập nhật kế hoạch 124', '2024-04-24 09:00:00', 'ND124'),
('HDHT125', 'Cập nhật kế hoạch 125', '2024-04-25 09:00:00', 'ND125'),
('HDHT126', 'Cập nhật kế hoạch 126', '2024-04-26 09:00:00', 'ND126'),
('HDHT127', 'Cập nhật kế hoạch 127', '2024-04-27 09:00:00', 'ND127'),
('HDHT128', 'Cập nhật kế hoạch 128', '2024-04-28 09:00:00', 'ND128'),
('HDHT129', 'Cập nhật kế hoạch 129', '2024-04-01 09:00:00', 'ND129'),
('HDHT130', 'Cập nhật kế hoạch 130', '2024-04-02 09:00:00', 'ND130'),
('HDHT131', 'Cập nhật kế hoạch 131', '2024-04-03 09:00:00', 'ND131'),
('HDHT132', 'Cập nhật kế hoạch 132', '2024-04-04 09:00:00', 'ND132'),
('HDHT133', 'Cập nhật kế hoạch 133', '2024-04-05 09:00:00', 'ND133'),
('HDHT134', 'Cập nhật kế hoạch 134', '2024-04-06 09:00:00', 'ND134'),
('HDHT135', 'Cập nhật kế hoạch 135', '2024-04-07 09:00:00', 'ND135'),
('HDHT136', 'Cập nhật kế hoạch 136', '2024-04-08 09:00:00', 'ND136'),
('HDHT137', 'Cập nhật kế hoạch 137', '2024-04-09 09:00:00', 'ND137'),
('HDHT138', 'Cập nhật kế hoạch 138', '2024-04-10 09:00:00', 'ND138'),
('HDHT139', 'Cập nhật kế hoạch 139', '2024-04-11 09:00:00', 'ND139'),
('HDHT140', 'Cập nhật kế hoạch 140', '2024-04-12 09:00:00', 'ND140'),
('HDHT141', 'Cập nhật kế hoạch 141', '2024-04-13 09:00:00', 'ND141'),
('HDHT142', 'Cập nhật kế hoạch 142', '2024-04-14 09:00:00', 'ND142'),
('HDHT143', 'Cập nhật kế hoạch 143', '2024-04-15 09:00:00', 'ND143'),
('HDHT144', 'Cập nhật kế hoạch 144', '2024-04-16 09:00:00', 'ND144'),
('HDHT145', 'Cập nhật kế hoạch 145', '2024-04-17 09:00:00', 'ND145'),
('HDHT146', 'Cập nhật kế hoạch 146', '2024-04-18 09:00:00', 'ND146'),
('HDHT147', 'Cập nhật kế hoạch 147', '2024-04-19 09:00:00', 'ND147'),
('HDHT148', 'Cập nhật kế hoạch 148', '2024-04-20 09:00:00', 'ND148'),
('HDHT149', 'Cập nhật kế hoạch 149', '2024-04-21 09:00:00', 'ND149'),
('HDHT150', 'Cập nhật kế hoạch 150', '2024-04-22 09:00:00', 'ND150'),
('HDHT151', 'Cập nhật kế hoạch 151', '2024-04-23 09:00:00', 'ND151'),
('HDHT152', 'Cập nhật kế hoạch 152', '2024-04-24 09:00:00', 'ND152'),
('HDHT153', 'Cập nhật kế hoạch 153', '2024-04-25 09:00:00', 'ND153'),
('HDHT154', 'Cập nhật kế hoạch 154', '2024-04-26 09:00:00', 'ND154'),
('HDHT155', 'Cập nhật kế hoạch 155', '2024-04-27 09:00:00', 'ND155'),
('HDHT156', 'Cập nhật kế hoạch 156', '2024-04-28 09:00:00', 'ND156'),
('HDHT157', 'Cập nhật kế hoạch 157', '2024-04-01 09:00:00', 'ND157'),
('HDHT158', 'Cập nhật kế hoạch 158', '2024-04-02 09:00:00', 'ND158'),
('HDHT159', 'Cập nhật kế hoạch 159', '2024-04-03 09:00:00', 'ND159'),
('HDHT160', 'Cập nhật kế hoạch 160', '2024-04-04 09:00:00', 'ND160'),
('HDHT161', 'Cập nhật kế hoạch 161', '2024-04-05 09:00:00', 'ND161'),
('HDHT162', 'Cập nhật kế hoạch 162', '2024-04-06 09:00:00', 'ND162'),
('HDHT163', 'Cập nhật kế hoạch 163', '2024-04-07 09:00:00', 'ND163'),
('HDHT164', 'Cập nhật kế hoạch 164', '2024-04-08 09:00:00', 'ND164'),
('HDHT165', 'Cập nhật kế hoạch 165', '2024-04-09 09:00:00', 'ND165'),
('HDHT166', 'Cập nhật kế hoạch 166', '2024-04-10 09:00:00', 'ND166'),
('HDHT167', 'Cập nhật kế hoạch 167', '2024-04-11 09:00:00', 'ND167'),
('HDHT168', 'Cập nhật kế hoạch 168', '2024-04-12 09:00:00', 'ND168'),
('HDHT169', 'Cập nhật kế hoạch 169', '2024-04-13 09:00:00', 'ND169'),
('HDHT170', 'Cập nhật kế hoạch 170', '2024-04-14 09:00:00', 'ND170'),
('HDHT171', 'Cập nhật kế hoạch 171', '2024-04-15 09:00:00', 'ND171'),
('HDHT172', 'Cập nhật kế hoạch 172', '2024-04-16 09:00:00', 'ND172'),
('HDHT173', 'Cập nhật kế hoạch 173', '2024-04-17 09:00:00', 'ND173'),
('HDHT174', 'Cập nhật kế hoạch 174', '2024-04-18 09:00:00', 'ND174'),
('HDHT175', 'Cập nhật kế hoạch 175', '2024-04-19 09:00:00', 'ND175'),
('HDHT176', 'Cập nhật kế hoạch 176', '2024-04-20 09:00:00', 'ND176'),
('HDHT177', 'Cập nhật kế hoạch 177', '2024-04-21 09:00:00', 'ND177'),
('HDHT178', 'Cập nhật kế hoạch 178', '2024-04-22 09:00:00', 'ND178'),
('HDHT179', 'Cập nhật kế hoạch 179', '2024-04-23 09:00:00', 'ND179'),
('HDHT180', 'Cập nhật kế hoạch 180', '2024-04-24 09:00:00', 'ND180'),
('HDHT181', 'Cập nhật kế hoạch 181', '2024-04-25 09:00:00', 'ND181'),
('HDHT182', 'Cập nhật kế hoạch 182', '2024-04-26 09:00:00', 'ND182'),
('HDHT183', 'Cập nhật kế hoạch 183', '2024-04-27 09:00:00', 'ND183'),
('HDHT184', 'Cập nhật kế hoạch 184', '2024-04-28 09:00:00', 'ND184'),
('HDHT185', 'Cập nhật kế hoạch 185', '2024-04-01 09:00:00', 'ND185'),
('HDHT186', 'Cập nhật kế hoạch 186', '2024-04-02 09:00:00', 'ND186'),
('HDHT187', 'Cập nhật kế hoạch 187', '2024-04-03 09:00:00', 'ND187'),
('HDHT188', 'Cập nhật kế hoạch 188', '2024-04-04 09:00:00', 'ND188'),
('HDHT189', 'Cập nhật kế hoạch 189', '2024-04-05 09:00:00', 'ND189'),
('HDHT190', 'Cập nhật kế hoạch 190', '2024-04-06 09:00:00', 'ND190'),
('HDHT191', 'Cập nhật kế hoạch 191', '2024-04-07 09:00:00', 'ND191'),
('HDHT192', 'Cập nhật kế hoạch 192', '2024-04-08 09:00:00', 'ND192'),
('HDHT193', 'Cập nhật kế hoạch 193', '2024-04-09 09:00:00', 'ND193'),
('HDHT194', 'Cập nhật kế hoạch 194', '2024-04-10 09:00:00', 'ND194'),
('HDHT195', 'Cập nhật kế hoạch 195', '2024-04-11 09:00:00', 'ND195'),
('HDHT196', 'Cập nhật kế hoạch 196', '2024-04-12 09:00:00', 'ND196'),
('HDHT197', 'Cập nhật kế hoạch 197', '2024-04-13 09:00:00', 'ND197'),
('HDHT198', 'Cập nhật kế hoạch 198', '2024-04-14 09:00:00', 'ND198'),
('HDHT199', 'Cập nhật kế hoạch 199', '2024-04-15 09:00:00', 'ND199'),
('HDHT200', 'Cập nhật kế hoạch 200', '2024-04-16 09:00:00', 'ND200'),
('HDHT201', 'Cập nhật kế hoạch 201', '2024-04-17 09:00:00', 'ND201'),
('HDHT202', 'Cập nhật kế hoạch 202', '2024-04-18 09:00:00', 'ND202'),
('HDHT203', 'Cập nhật kế hoạch 203', '2024-04-19 09:00:00', 'ND203'),
('HDHT204', 'Cập nhật kế hoạch 204', '2024-04-20 09:00:00', 'ND204'),
('HDHT205', 'Cập nhật kế hoạch 205', '2024-04-21 09:00:00', 'ND205'),
('HDHT206', 'Cập nhật kế hoạch 206', '2024-04-22 09:00:00', 'ND206'),
('HDHT207', 'Cập nhật kế hoạch 207', '2024-04-23 09:00:00', 'ND207'),
('HDHT208', 'Cập nhật kế hoạch 208', '2024-04-24 09:00:00', 'ND208'),
('HDHT209', 'Cập nhật kế hoạch 209', '2024-04-25 09:00:00', 'ND209'),
('HDHT210', 'Cập nhật kế hoạch 210', '2024-04-26 09:00:00', 'ND210'),
('HDHT211', 'Cập nhật kế hoạch 211', '2024-04-27 09:00:00', 'ND211'),
('HDHT212', 'Cập nhật kế hoạch 212', '2024-04-28 09:00:00', 'ND212'),
('HDHT213', 'Cập nhật kế hoạch 213', '2024-04-01 09:00:00', 'ND213'),
('HDHT214', 'Cập nhật kế hoạch 214', '2024-04-02 09:00:00', 'ND214'),
('HDHT215', 'Cập nhật kế hoạch 215', '2024-04-03 09:00:00', 'ND215'),
('HDHT216', 'Cập nhật kế hoạch 216', '2024-04-04 09:00:00', 'ND216'),
('HDHT217', 'Cập nhật kế hoạch 217', '2024-04-05 09:00:00', 'ND217'),
('HDHT218', 'Cập nhật kế hoạch 218', '2024-04-06 09:00:00', 'ND218'),
('HDHT219', 'Cập nhật kế hoạch 219', '2024-04-07 09:00:00', 'ND219'),
('HDHT220', 'Cập nhật kế hoạch 220', '2024-04-08 09:00:00', 'ND220');

INSERT INTO `bien_ban_danh_gia_dot_xuat` (`IdBienBanDanhGiaDX`, `ThoiGian`, `TongTCD`, `TongTCKD`, `KetQua`, `IdXuong`, `IdNhanVien`) VALUES
('BBDGDX001', '2024-04-11 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX101', '2024-04-01 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX102', '2024-04-02 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX103', '2024-04-03 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX104', '2024-04-04 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX105', '2024-04-05 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX106', '2024-04-06 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX107', '2024-04-07 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX108', '2024-04-08 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX109', '2024-04-09 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX110', '2024-04-10 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX111', '2024-04-11 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX112', '2024-04-12 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX113', '2024-04-13 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX114', '2024-04-14 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX115', '2024-04-15 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX116', '2024-04-16 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX117', '2024-04-17 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX118', '2024-04-18 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX119', '2024-04-19 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX120', '2024-04-20 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX121', '2024-04-21 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX122', '2024-04-22 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX123', '2024-04-23 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX124', '2024-04-24 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX125', '2024-04-25 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX126', '2024-04-26 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX127', '2024-04-27 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX128', '2024-04-28 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX129', '2024-04-01 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX130', '2024-04-02 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX131', '2024-04-03 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX132', '2024-04-04 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX133', '2024-04-05 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX134', '2024-04-06 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX135', '2024-04-07 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX136', '2024-04-08 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX137', '2024-04-09 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX138', '2024-04-10 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX139', '2024-04-11 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX140', '2024-04-12 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX141', '2024-04-13 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX142', '2024-04-14 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX143', '2024-04-15 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX144', '2024-04-16 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX145', '2024-04-17 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX146', '2024-04-18 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX147', '2024-04-19 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX148', '2024-04-20 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX149', '2024-04-21 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX150', '2024-04-22 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX151', '2024-04-23 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX152', '2024-04-24 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX153', '2024-04-25 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX154', '2024-04-26 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX155', '2024-04-27 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX156', '2024-04-28 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX157', '2024-04-01 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX158', '2024-04-02 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX159', '2024-04-03 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX160', '2024-04-04 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX161', '2024-04-05 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX162', '2024-04-06 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX163', '2024-04-07 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX164', '2024-04-08 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX165', '2024-04-09 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX166', '2024-04-10 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX167', '2024-04-11 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX168', '2024-04-12 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX169', '2024-04-13 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX170', '2024-04-14 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX171', '2024-04-15 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX172', '2024-04-16 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX173', '2024-04-17 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX174', '2024-04-18 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX175', '2024-04-19 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX176', '2024-04-20 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX177', '2024-04-21 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX178', '2024-04-22 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX179', '2024-04-23 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX180', '2024-04-24 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX181', '2024-04-25 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX182', '2024-04-26 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX183', '2024-04-27 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX184', '2024-04-28 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX185', '2024-04-01 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX186', '2024-04-02 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX187', '2024-04-03 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX188', '2024-04-04 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX189', '2024-04-05 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX190', '2024-04-06 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX191', '2024-04-07 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX192', '2024-04-08 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX193', '2024-04-09 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX194', '2024-04-10 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX195', '2024-04-11 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX196', '2024-04-12 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX197', '2024-04-13 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX198', '2024-04-14 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX199', '2024-04-15 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX200', '2024-04-16 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX201', '2024-04-17 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX202', '2024-04-18 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX203', '2024-04-19 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX204', '2024-04-20 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX205', '2024-04-21 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX206', '2024-04-22 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX207', '2024-04-23 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX208', '2024-04-24 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX209', '2024-04-25 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX210', '2024-04-26 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX211', '2024-04-27 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX212', '2024-04-28 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX213', '2024-04-01 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX214', '2024-04-02 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX215', '2024-04-03 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX216', '2024-04-04 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX217', '2024-04-05 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX218', '2024-04-06 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01'),
('BBDGDX219', '2024-04-07 10:00:00', 90, 5, 'Đạt', 'XUONG01', 'NV_QC01'),
('BBDGDX220', '2024-04-08 10:00:00', 90, 5, 'Đạt', 'XUONG02', 'NV_QC01');

INSERT INTO `ttct_bien_ban_danh_gia_dot_xuat` (`IdTTCTBBDGDX`, `LoaiTieuChi`, `TieuChi`, `DiemDG`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaDX`) VALUES
('TTDX001', 'Ngoại quan', 'Bề mặt sạch', 45, 'Đạt', NULL, 'BBDGDX001'),
('TTDX002', 'Kỹ thuật', 'Lắp ráp đúng chuẩn', 45, 'Đạt', NULL, 'BBDGDX001'),
('TTDX101', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX101'),
('TTDX102', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX102'),
('TTDX103', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX103'),
('TTDX104', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX104'),
('TTDX105', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX105'),
('TTDX106', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX106'),
('TTDX107', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX107'),
('TTDX108', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX108'),
('TTDX109', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX109'),
('TTDX110', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX110'),
('TTDX111', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX111'),
('TTDX112', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX112'),
('TTDX113', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX113'),
('TTDX114', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX114'),
('TTDX115', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX115'),
('TTDX116', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX116'),
('TTDX117', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX117'),
('TTDX118', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX118'),
('TTDX119', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX119'),
('TTDX120', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX120'),
('TTDX121', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX121'),
('TTDX122', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX122'),
('TTDX123', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX123'),
('TTDX124', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX124'),
('TTDX125', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX125'),
('TTDX126', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX126'),
('TTDX127', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX127'),
('TTDX128', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX128'),
('TTDX129', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX129'),
('TTDX130', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX130'),
('TTDX131', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX131'),
('TTDX132', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX132'),
('TTDX133', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX133'),
('TTDX134', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX134'),
('TTDX135', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX135'),
('TTDX136', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX136'),
('TTDX137', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX137'),
('TTDX138', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX138'),
('TTDX139', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX139'),
('TTDX140', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX140'),
('TTDX141', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX141'),
('TTDX142', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX142'),
('TTDX143', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX143'),
('TTDX144', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX144'),
('TTDX145', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX145'),
('TTDX146', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX146'),
('TTDX147', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX147'),
('TTDX148', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX148'),
('TTDX149', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX149'),
('TTDX150', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX150'),
('TTDX151', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX151'),
('TTDX152', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX152'),
('TTDX153', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX153'),
('TTDX154', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX154'),
('TTDX155', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX155'),
('TTDX156', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX156'),
('TTDX157', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX157'),
('TTDX158', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX158'),
('TTDX159', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX159'),
('TTDX160', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX160'),
('TTDX161', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX161'),
('TTDX162', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX162'),
('TTDX163', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX163'),
('TTDX164', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX164'),
('TTDX165', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX165'),
('TTDX166', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX166'),
('TTDX167', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX167'),
('TTDX168', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX168'),
('TTDX169', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX169'),
('TTDX170', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX170'),
('TTDX171', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX171'),
('TTDX172', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX172'),
('TTDX173', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX173'),
('TTDX174', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX174'),
('TTDX175', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX175'),
('TTDX176', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX176'),
('TTDX177', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX177'),
('TTDX178', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX178'),
('TTDX179', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX179'),
('TTDX180', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX180'),
('TTDX181', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX181'),
('TTDX182', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX182'),
('TTDX183', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX183'),
('TTDX184', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX184'),
('TTDX185', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX185'),
('TTDX186', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX186'),
('TTDX187', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX187'),
('TTDX188', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX188'),
('TTDX189', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX189'),
('TTDX190', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX190'),
('TTDX191', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX191'),
('TTDX192', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX192'),
('TTDX193', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX193'),
('TTDX194', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX194'),
('TTDX195', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX195'),
('TTDX196', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX196'),
('TTDX197', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX197'),
('TTDX198', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX198'),
('TTDX199', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX199'),
('TTDX200', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX200'),
('TTDX201', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX201'),
('TTDX202', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX202'),
('TTDX203', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX203'),
('TTDX204', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX204'),
('TTDX205', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX205'),
('TTDX206', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX206'),
('TTDX207', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX207'),
('TTDX208', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX208'),
('TTDX209', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX209'),
('TTDX210', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX210'),
('TTDX211', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX211'),
('TTDX212', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX212'),
('TTDX213', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX213'),
('TTDX214', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX214'),
('TTDX215', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX215'),
('TTDX216', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX216'),
('TTDX217', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX217'),
('TTDX218', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX218'),
('TTDX219', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX219'),
('TTDX220', 'Ngoại quan', 'Kiểm tra linh kiện', 45, 'Đạt', NULL, 'BBDGDX220');

INSERT INTO `bien_ban_danh_gia_thanh_pham` (`IdBienBanDanhGiaSP`, `ThoiGian`, `TongTCD`, `TongTCKD`, `KetQua`, `IdLo`) VALUES
('BBDGTP001', '2024-04-18 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP101', '2024-04-01 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP102', '2024-04-02 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP103', '2024-04-03 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP104', '2024-04-04 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP105', '2024-04-05 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP106', '2024-04-06 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP107', '2024-04-07 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP108', '2024-04-08 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP109', '2024-04-09 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP110', '2024-04-10 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP111', '2024-04-11 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP112', '2024-04-12 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP113', '2024-04-13 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP114', '2024-04-14 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP115', '2024-04-15 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP116', '2024-04-16 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP117', '2024-04-17 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP118', '2024-04-18 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP119', '2024-04-19 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP120', '2024-04-20 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP121', '2024-04-21 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP122', '2024-04-22 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP123', '2024-04-23 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP124', '2024-04-24 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP125', '2024-04-25 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP126', '2024-04-26 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP127', '2024-04-27 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP128', '2024-04-28 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP129', '2024-04-01 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP130', '2024-04-02 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP131', '2024-04-03 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP132', '2024-04-04 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP133', '2024-04-05 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP134', '2024-04-06 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP135', '2024-04-07 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP136', '2024-04-08 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP137', '2024-04-09 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP138', '2024-04-10 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP139', '2024-04-11 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP140', '2024-04-12 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP141', '2024-04-13 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP142', '2024-04-14 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP143', '2024-04-15 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP144', '2024-04-16 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP145', '2024-04-17 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP146', '2024-04-18 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP147', '2024-04-19 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP148', '2024-04-20 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP149', '2024-04-21 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP150', '2024-04-22 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP151', '2024-04-23 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP152', '2024-04-24 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP153', '2024-04-25 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP154', '2024-04-26 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP155', '2024-04-27 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP156', '2024-04-28 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP157', '2024-04-01 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP158', '2024-04-02 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP159', '2024-04-03 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP160', '2024-04-04 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP161', '2024-04-05 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP162', '2024-04-06 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP163', '2024-04-07 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP164', '2024-04-08 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP165', '2024-04-09 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP166', '2024-04-10 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP167', '2024-04-11 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP168', '2024-04-12 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP169', '2024-04-13 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP170', '2024-04-14 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP171', '2024-04-15 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP172', '2024-04-16 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP173', '2024-04-17 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP174', '2024-04-18 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP175', '2024-04-19 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP176', '2024-04-20 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP177', '2024-04-21 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP178', '2024-04-22 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP179', '2024-04-23 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP180', '2024-04-24 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP181', '2024-04-25 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP182', '2024-04-26 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP183', '2024-04-27 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP184', '2024-04-28 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP185', '2024-04-01 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP186', '2024-04-02 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP187', '2024-04-03 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP188', '2024-04-04 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP189', '2024-04-05 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP190', '2024-04-06 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP191', '2024-04-07 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP192', '2024-04-08 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP193', '2024-04-09 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP194', '2024-04-10 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP195', '2024-04-11 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP196', '2024-04-12 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP197', '2024-04-13 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP198', '2024-04-14 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP199', '2024-04-15 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP200', '2024-04-16 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP201', '2024-04-17 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP202', '2024-04-18 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP203', '2024-04-19 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP204', '2024-04-20 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP205', '2024-04-21 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP206', '2024-04-22 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP207', '2024-04-23 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP208', '2024-04-24 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP209', '2024-04-25 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP210', '2024-04-26 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP211', '2024-04-27 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP212', '2024-04-28 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP213', '2024-04-01 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP214', '2024-04-02 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP215', '2024-04-03 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP216', '2024-04-04 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP217', '2024-04-05 15:00:00', 95, 3, 'Đạt', 'LO003'),
('BBDGTP218', '2024-04-06 15:00:00', 95, 3, 'Đạt', 'LO001'),
('BBDGTP219', '2024-04-07 15:00:00', 95, 3, 'Đạt', 'LO002'),
('BBDGTP220', '2024-04-08 15:00:00', 95, 3, 'Đạt', 'LO003');

INSERT INTO `ttct_bien_ban_danh_gia_thanh_pham` (`IdTTCTBBDGTP`, `Tieuchi`, `DiemD`, `GhiChu`, `HinhAnh`, `IdBienBanDanhGiaSP`) VALUES
('TTTP001', 'Kiểm tra keycap', 50, 'Đạt', NULL, 'BBDGTP001'),
('TTTP002', 'Kiểm tra switch', 45, 'Đạt', NULL, 'BBDGTP001'),
('TTTP101', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP101'),
('TTTP102', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP102'),
('TTTP103', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP103'),
('TTTP104', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP104'),
('TTTP105', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP105'),
('TTTP106', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP106'),
('TTTP107', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP107'),
('TTTP108', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP108'),
('TTTP109', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP109'),
('TTTP110', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP110'),
('TTTP111', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP111'),
('TTTP112', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP112'),
('TTTP113', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP113'),
('TTTP114', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP114'),
('TTTP115', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP115'),
('TTTP116', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP116'),
('TTTP117', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP117'),
('TTTP118', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP118'),
('TTTP119', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP119'),
('TTTP120', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP120'),
('TTTP121', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP121'),
('TTTP122', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP122'),
('TTTP123', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP123'),
('TTTP124', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP124'),
('TTTP125', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP125'),
('TTTP126', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP126'),
('TTTP127', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP127'),
('TTTP128', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP128'),
('TTTP129', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP129'),
('TTTP130', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP130'),
('TTTP131', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP131'),
('TTTP132', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP132'),
('TTTP133', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP133'),
('TTTP134', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP134'),
('TTTP135', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP135'),
('TTTP136', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP136'),
('TTTP137', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP137'),
('TTTP138', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP138'),
('TTTP139', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP139'),
('TTTP140', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP140'),
('TTTP141', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP141'),
('TTTP142', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP142'),
('TTTP143', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP143'),
('TTTP144', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP144'),
('TTTP145', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP145'),
('TTTP146', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP146'),
('TTTP147', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP147'),
('TTTP148', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP148'),
('TTTP149', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP149'),
('TTTP150', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP150'),
('TTTP151', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP151'),
('TTTP152', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP152'),
('TTTP153', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP153'),
('TTTP154', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP154'),
('TTTP155', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP155'),
('TTTP156', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP156'),
('TTTP157', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP157'),
('TTTP158', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP158'),
('TTTP159', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP159'),
('TTTP160', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP160'),
('TTTP161', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP161'),
('TTTP162', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP162'),
('TTTP163', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP163'),
('TTTP164', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP164'),
('TTTP165', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP165'),
('TTTP166', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP166'),
('TTTP167', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP167'),
('TTTP168', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP168'),
('TTTP169', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP169'),
('TTTP170', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP170'),
('TTTP171', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP171'),
('TTTP172', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP172'),
('TTTP173', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP173'),
('TTTP174', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP174'),
('TTTP175', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP175'),
('TTTP176', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP176'),
('TTTP177', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP177'),
('TTTP178', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP178'),
('TTTP179', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP179'),
('TTTP180', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP180'),
('TTTP181', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP181'),
('TTTP182', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP182'),
('TTTP183', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP183'),
('TTTP184', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP184'),
('TTTP185', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP185'),
('TTTP186', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP186'),
('TTTP187', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP187'),
('TTTP188', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP188'),
('TTTP189', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP189'),
('TTTP190', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP190'),
('TTTP191', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP191'),
('TTTP192', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP192'),
('TTTP193', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP193'),
('TTTP194', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP194'),
('TTTP195', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP195'),
('TTTP196', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP196'),
('TTTP197', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP197'),
('TTTP198', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP198'),
('TTTP199', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP199'),
('TTTP200', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP200'),
('TTTP201', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP201'),
('TTTP202', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP202'),
('TTTP203', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP203'),
('TTTP204', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP204'),
('TTTP205', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP205'),
('TTTP206', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP206'),
('TTTP207', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP207'),
('TTTP208', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP208'),
('TTTP209', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP209'),
('TTTP210', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP210'),
('TTTP211', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP211'),
('TTTP212', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP212'),
('TTTP213', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP213'),
('TTTP214', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP214'),
('TTTP215', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP215'),
('TTTP216', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP216'),
('TTTP217', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP217'),
('TTTP218', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP218'),
('TTTP219', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP219'),
('TTTP220', 'Kiểm tra chức năng', 48, 'Đạt', NULL, 'BBDGTP220');

INSERT INTO `thanh_pham` (`IdThanhPham`, `TenThanhPham`, `YeuCau`, `DonGia`, `LoaiTP`, `IdLo`) VALUES
('TP001', 'Bàn phím MecaKey 75 Pro', 'QC đạt chuẩn', 2500000, 'Bàn phím cơ', 'LO001'),
('TP002', 'Bàn phím MecaKey 65 Lite', 'QC đạt chuẩn', 1900000, 'Bàn phím cơ', 'LO002'),
('TP101', 'Thành phẩm lô 101', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP102', 'Thành phẩm lô 102', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP103', 'Thành phẩm lô 103', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP104', 'Thành phẩm lô 104', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP105', 'Thành phẩm lô 105', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP106', 'Thành phẩm lô 106', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP107', 'Thành phẩm lô 107', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP108', 'Thành phẩm lô 108', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP109', 'Thành phẩm lô 109', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP110', 'Thành phẩm lô 110', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP111', 'Thành phẩm lô 111', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP112', 'Thành phẩm lô 112', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP113', 'Thành phẩm lô 113', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP114', 'Thành phẩm lô 114', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP115', 'Thành phẩm lô 115', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP116', 'Thành phẩm lô 116', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP117', 'Thành phẩm lô 117', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP118', 'Thành phẩm lô 118', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP119', 'Thành phẩm lô 119', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP120', 'Thành phẩm lô 120', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP121', 'Thành phẩm lô 121', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP122', 'Thành phẩm lô 122', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP123', 'Thành phẩm lô 123', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP124', 'Thành phẩm lô 124', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP125', 'Thành phẩm lô 125', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP126', 'Thành phẩm lô 126', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP127', 'Thành phẩm lô 127', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP128', 'Thành phẩm lô 128', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP129', 'Thành phẩm lô 129', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP130', 'Thành phẩm lô 130', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP131', 'Thành phẩm lô 131', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP132', 'Thành phẩm lô 132', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP133', 'Thành phẩm lô 133', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP134', 'Thành phẩm lô 134', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP135', 'Thành phẩm lô 135', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP136', 'Thành phẩm lô 136', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP137', 'Thành phẩm lô 137', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP138', 'Thành phẩm lô 138', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP139', 'Thành phẩm lô 139', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP140', 'Thành phẩm lô 140', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP141', 'Thành phẩm lô 141', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP142', 'Thành phẩm lô 142', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP143', 'Thành phẩm lô 143', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP144', 'Thành phẩm lô 144', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP145', 'Thành phẩm lô 145', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP146', 'Thành phẩm lô 146', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP147', 'Thành phẩm lô 147', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP148', 'Thành phẩm lô 148', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP149', 'Thành phẩm lô 149', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP150', 'Thành phẩm lô 150', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP151', 'Thành phẩm lô 151', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP152', 'Thành phẩm lô 152', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP153', 'Thành phẩm lô 153', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP154', 'Thành phẩm lô 154', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP155', 'Thành phẩm lô 155', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP156', 'Thành phẩm lô 156', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP157', 'Thành phẩm lô 157', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP158', 'Thành phẩm lô 158', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP159', 'Thành phẩm lô 159', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP160', 'Thành phẩm lô 160', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP161', 'Thành phẩm lô 161', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP162', 'Thành phẩm lô 162', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP163', 'Thành phẩm lô 163', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP164', 'Thành phẩm lô 164', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP165', 'Thành phẩm lô 165', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP166', 'Thành phẩm lô 166', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP167', 'Thành phẩm lô 167', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP168', 'Thành phẩm lô 168', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP169', 'Thành phẩm lô 169', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP170', 'Thành phẩm lô 170', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP171', 'Thành phẩm lô 171', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP172', 'Thành phẩm lô 172', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP173', 'Thành phẩm lô 173', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP174', 'Thành phẩm lô 174', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP175', 'Thành phẩm lô 175', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP176', 'Thành phẩm lô 176', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP177', 'Thành phẩm lô 177', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP178', 'Thành phẩm lô 178', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP179', 'Thành phẩm lô 179', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP180', 'Thành phẩm lô 180', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP181', 'Thành phẩm lô 181', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP182', 'Thành phẩm lô 182', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP183', 'Thành phẩm lô 183', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP184', 'Thành phẩm lô 184', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP185', 'Thành phẩm lô 185', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP186', 'Thành phẩm lô 186', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP187', 'Thành phẩm lô 187', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP188', 'Thành phẩm lô 188', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP189', 'Thành phẩm lô 189', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP190', 'Thành phẩm lô 190', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP191', 'Thành phẩm lô 191', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP192', 'Thành phẩm lô 192', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP193', 'Thành phẩm lô 193', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP194', 'Thành phẩm lô 194', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP195', 'Thành phẩm lô 195', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP196', 'Thành phẩm lô 196', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP197', 'Thành phẩm lô 197', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP198', 'Thành phẩm lô 198', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP199', 'Thành phẩm lô 199', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP200', 'Thành phẩm lô 200', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP201', 'Thành phẩm lô 201', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP202', 'Thành phẩm lô 202', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP203', 'Thành phẩm lô 203', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP204', 'Thành phẩm lô 204', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP205', 'Thành phẩm lô 205', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP206', 'Thành phẩm lô 206', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP207', 'Thành phẩm lô 207', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP208', 'Thành phẩm lô 208', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP209', 'Thành phẩm lô 209', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP210', 'Thành phẩm lô 210', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP211', 'Thành phẩm lô 211', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP212', 'Thành phẩm lô 212', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP213', 'Thành phẩm lô 213', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP214', 'Thành phẩm lô 214', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP215', 'Thành phẩm lô 215', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP216', 'Thành phẩm lô 216', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP217', 'Thành phẩm lô 217', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003'),
('TP218', 'Thành phẩm lô 218', 'QC đạt chuẩn', 1800000, 'Bàn phím cơ', 'LO001'),
('TP219', 'Thành phẩm lô 219', 'QC đạt chuẩn', 2000000, 'Bàn phím cơ', 'LO002'),
('TP220', 'Thành phẩm lô 220', 'QC đạt chuẩn', 2200000, 'Bàn phím cơ', 'LO003');

INSERT INTO `cau_hinh_thong_bao` (`MaCauHinh`, `GiaTri`, `MoTa`) VALUES
('workshop_channel', 'workshop', 'Kênh thông báo xưởng'),
('warehouse_channel', 'warehouse', 'Kênh thông báo kho'),
('warehouse_recipients', '[\"VT_KHO_TRUONG\",\"VT_NHANVIEN_KHO\"]', 'Nhận thông báo kho'),
('inventory_alert_channel', 'inventory_alert', 'Kênh cảnh báo tồn kho'),
('inventory_alert_recipients', '[\"VT_KHO_TRUONG\",\"VT_NHANVIEN_KHO\"]', 'Nhận cảnh báo tồn kho'),
('board_channel', 'board_notification', 'Kênh thông báo bảng điều hành');

SET FOREIGN_KEY_CHECKS=1;
