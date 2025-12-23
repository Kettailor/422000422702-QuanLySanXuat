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
('XUONG01', 'Xưởng Lắp Ráp A', 'Bình Dương', '2025-12-10', 40, 25, 15, 'Lắp ráp linh kiện', 500, 320, 'Lắp ráp', 'Hoạt động', 'Xưởng lắp ráp bàn phím cơ', 'NV_XU01'),
('XUONG02', 'Xưởng Hoàn Thiện B', 'Bình Dương', '2025-12-20', 30, 20, 12, 'Hoàn thiện & QC', 400, 250, 'Hoàn thiện', 'Hoạt động', 'Xưởng hoàn thiện và đóng gói', 'NV_XU02'),
('XUONG03', 'Xưởng Gia Công C', 'Bình Dương', '2025-12-03', 28, 22, 14, 'Gia công linh kiện', 420, 280, 'Gia công', 'Hoạt động', 'Xưởng gia công chi tiết cơ khí', 'NV_XU03'),
('XUONG04', 'Xưởng Lắp Ráp D', 'Bình Dương', '2025-12-04', 32, 24, 16, 'Lắp ráp module', 460, 300, 'Lắp ráp', 'Hoạt động', 'Xưởng lắp ráp module bàn phím', 'NV_XU04'),
('XUONG05', 'Xưởng Sơn Phủ E', 'Bình Dương', '2025-12-05', 26, 18, 12, 'Sơn phủ & xử lý bề mặt', 380, 240, 'Sơn phủ', 'Hoạt động', 'Xưởng xử lý bề mặt vỏ nhôm', 'NV_XU05'),
('XUONG06', 'Xưởng Hoàn Thiện F', 'Bình Dương', '2025-12-06', 29, 21, 13, 'Hoàn thiện & đóng gói', 410, 265, 'Hoàn thiện', 'Hoạt động', 'Xưởng hoàn thiện và đóng gói', 'NV_XU06'),
('XUONG07', 'Xưởng QC G', 'Bình Dương', '2025-12-07', 24, 19, 11, 'Kiểm tra chất lượng', 360, 230, 'QC', 'Hoạt động', 'Xưởng kiểm tra chất lượng', 'NV_XU07'),
('XUONG08', 'Xưởng Lắp Ráp H', 'Bình Dương', '2025-12-08', 33, 26, 17, 'Lắp ráp cơ khí', 480, 310, 'Lắp ráp', 'Hoạt động', 'Xưởng lắp ráp cơ khí', 'NV_XU08'),
('XUONG09', 'Xưởng In Ấn I', 'Bình Dương', '2025-12-09', 20, 15, 9, 'In ấn & khắc laser', 300, 190, 'In ấn', 'Hoạt động', 'Xưởng in khắc logo', 'NV_XU09'),
('XUONG10', 'Xưởng SMT J', 'Bình Dương', '2025-12-10', 36, 27, 18, 'Gắn linh kiện SMT', 520, 340, 'SMT', 'Hoạt động', 'Xưởng gắn linh kiện SMT', 'NV_XU10'),
('XUONG11', 'Xưởng Đóng Gói K', 'Bình Dương', '2025-12-11', 22, 17, 10, 'Đóng gói', 310, 200, 'Đóng gói', 'Hoạt động', 'Xưởng đóng gói thành phẩm', 'NV_XU11'),
('XUONG12', 'Xưởng Lắp Ráp L', 'Bình Dương', '2025-12-12', 34, 28, 18, 'Lắp ráp cuối', 540, 360, 'Lắp ráp', 'Hoạt động', 'Xưởng lắp ráp cuối', 'NV_XU12'),
('XUONG13', 'Xưởng Sản Xuất M', 'Bình Dương', '2025-12-13', 38, 29, 19, 'Sản xuất tổng hợp', 560, 370, 'Sản xuất', 'Hoạt động', 'Xưởng sản xuất tổng hợp', 'NV_XU13'),
('XUONG14', 'Xưởng Tối Ưu N', 'Bình Dương', '2025-12-14', 27, 20, 12, 'Tối ưu quy trình', 390, 255, 'Tối ưu', 'Hoạt động', 'Xưởng tối ưu quy trình', 'NV_XU14');

INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`, `IdVaiTro`) VALUES
('NV_ADMIN', 'Nguyễn Văn An', '2025-12-15', 1, 'Quản trị hệ thống', 4, 'Đang làm việc', 'Thủ Đức, TP.HCM', '2025-12-01 08:00:00', NULL, NULL, 'VT_ADMIN'),
('NV_GD01', 'Trần Thị Minh', '2025-12-22', 0, 'Giám đốc điều hành', 6, 'Đang làm việc', 'Quận 1, TP.HCM', '2025-12-15 08:00:00', NULL, NULL, 'VT_BAN_GIAM_DOC'),
('NV_XU01', 'Lê Quốc Huy', '2025-12-11', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG01', 'VT_QUANLY_XUONG'),
('NV_XU02', 'Phạm Thu Hà', '2025-12-05', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG02', 'VT_QUANLY_XUONG'),
('NV_KT01', 'Đặng Thị Hạnh', '2025-12-19', 0, 'Kế toán tổng hợp', 4, 'Đang làm việc', 'Phú Nhuận, TP.HCM', '2025-12-12 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV_KHO01', 'Vũ Minh Tuấn', '2025-12-30', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG01', 'VT_KHO_TRUONG'),
('NV_KHO02', 'Ngô Hải Yến', '2025-12-12', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG02', 'VT_KHO_TRUONG'),
('NV_XU03', 'Phạm Minh Tâm', '2025-12-03', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG03', 'VT_QUANLY_XUONG'),
('NV_XU04', 'Nguyễn Phương Linh', '2025-12-04', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG04', 'VT_QUANLY_XUONG'),
('NV_XU05', 'Lê Quốc Bình', '2025-12-05', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG05', 'VT_QUANLY_XUONG'),
('NV_XU06', 'Trần Ngọc Mai', '2025-12-06', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG06', 'VT_QUANLY_XUONG'),
('NV_XU07', 'Phạm Thanh Hà', '2025-12-07', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thủ Dầu Một, Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG07', 'VT_QUANLY_XUONG'),
('NV_XU08', 'Đặng Quốc Huy', '2025-12-08', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG08', 'VT_QUANLY_XUONG'),
('NV_XU09', 'Ngô Gia Hân', '2025-12-09', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG09', 'VT_QUANLY_XUONG'),
('NV_XU10', 'Vũ Minh Long', '2025-12-10', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG10', 'VT_QUANLY_XUONG'),
('NV_XU11', 'Bùi Ngọc Anh', '2025-12-11', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG11', 'VT_QUANLY_XUONG'),
('NV_XU12', 'Phan Quốc Duy', '2025-12-12', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Thủ Dầu Một, Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG12', 'VT_QUANLY_XUONG'),
('NV_XU13', 'Nguyễn Thảo Nhi', '2025-12-13', 0, 'Quản lý xưởng', 5, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG13', 'VT_QUANLY_XUONG'),
('NV_XU14', 'Lê Minh Hoàng', '2025-12-14', 1, 'Quản lý xưởng', 5, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG14', 'VT_QUANLY_XUONG'),
('NV_KHO03', 'Đặng Thuỳ Trang', '2025-12-03', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG03', 'VT_KHO_TRUONG'),
('NV_KHO04', 'Nguyễn Quốc Vũ', '2025-12-04', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG04', 'VT_KHO_TRUONG'),
('NV_KHO05', 'Lê Thị Hồng', '2025-12-05', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG05', 'VT_KHO_TRUONG'),
('NV_KHO06', 'Trần Quốc Tuấn', '2025-12-06', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG06', 'VT_KHO_TRUONG'),
('NV_KHO07', 'Phạm Ngọc Lan', '2025-12-07', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Thủ Dầu Một, Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG07', 'VT_KHO_TRUONG'),
('NV_KHO08', 'Ngô Minh Hùng', '2025-12-08', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG08', 'VT_KHO_TRUONG'),
('NV_KHO09', 'Vũ Thanh Trúc', '2025-12-09', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG09', 'VT_KHO_TRUONG'),
('NV_KHO10', 'Bùi Văn Phúc', '2025-12-10', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG10', 'VT_KHO_TRUONG'),
('NV_KHO11', 'Phan Thuỳ Dung', '2025-12-11', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG11', 'VT_KHO_TRUONG'),
('NV_KHO12', 'Nguyễn Minh Khoa', '2025-12-12', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Thủ Dầu Một, Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG12', 'VT_KHO_TRUONG'),
('NV_KHO13', 'Lê Thị Yến', '2025-12-13', 0, 'Kho trưởng', 4, 'Đang làm việc', 'Tân Uyên, Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG13', 'VT_KHO_TRUONG'),
('NV_KHO14', 'Đỗ Minh Tuấn', '2025-12-14', 1, 'Kho trưởng', 4, 'Đang làm việc', 'Bến Cát, Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG14', 'VT_KHO_TRUONG'),
('NV_KNV01', 'Bùi Văn Phúc', '2025-12-17', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV_KD01', 'Đỗ Thanh Mai', '2025-12-09', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'Quận 7, TP.HCM', '2025-12-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV_QC01', 'Hoàng Gia Bảo', '2025-12-02', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV_SX01', 'Phan Quốc Thịnh', '2025-12-21', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Thuận An, Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV_SX02', 'Nguyễn Thảo Vy', '2025-12-13', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Dĩ An, Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV101', 'Nhân viên 101', '2025-12-01', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV102', 'Nhân viên 102', '2025-12-02', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-02 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV103', 'Nhân viên 103', '2025-12-03', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV104', 'Nhân viên 104', '2025-12-04', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-04 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV105', 'Nhân viên 105', '2025-12-05', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-05 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV106', 'Nhân viên 106', '2025-12-06', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV107', 'Nhân viên 107', '2025-12-07', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV108', 'Nhân viên 108', '2025-12-08', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV109', 'Nhân viên 109', '2025-12-09', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-09 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV110', 'Nhân viên 110', '2025-12-10', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-10 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV111', 'Nhân viên 111', '2025-12-11', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV112', 'Nhân viên 112', '2025-12-12', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV113', 'Nhân viên 113', '2025-12-13', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV114', 'Nhân viên 114', '2025-12-14', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-14 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV115', 'Nhân viên 115', '2025-12-15', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-15 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV116', 'Nhân viên 116', '2025-12-16', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-16 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV117', 'Nhân viên 117', '2025-12-17', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-17 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV118', 'Nhân viên 118', '2025-12-18', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-18 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV119', 'Nhân viên 119', '2025-12-19', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-19 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV120', 'Nhân viên 120', '2025-12-20', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-20 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV121', 'Nhân viên 121', '2025-12-21', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-21 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV122', 'Nhân viên 122', '2025-12-22', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-22 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV123', 'Nhân viên 123', '2025-12-23', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-23 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV124', 'Nhân viên 124', '2025-12-24', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-24 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV125', 'Nhân viên 125', '2025-12-25', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-25 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV126', 'Nhân viên 126', '2025-12-26', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-26 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV127', 'Nhân viên 127', '2025-12-27', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-27 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV128', 'Nhân viên 128', '2025-12-01', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV129', 'Nhân viên 129', '2025-12-02', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-02 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV130', 'Nhân viên 130', '2025-12-03', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-03 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV131', 'Nhân viên 131', '2025-12-04', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV132', 'Nhân viên 132', '2025-12-05', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV133', 'Nhân viên 133', '2025-12-06', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV134', 'Nhân viên 134', '2025-12-07', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-07 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV135', 'Nhân viên 135', '2025-12-08', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-08 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV136', 'Nhân viên 136', '2025-12-09', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV137', 'Nhân viên 137', '2025-12-10', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV138', 'Nhân viên 138', '2025-12-11', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV139', 'Nhân viên 139', '2025-12-12', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-12 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV140', 'Nhân viên 140', '2025-12-13', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-13 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV141', 'Nhân viên 141', '2025-12-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV142', 'Nhân viên 142', '2025-12-15', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV143', 'Nhân viên 143', '2025-12-16', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-16 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV144', 'Nhân viên 144', '2025-12-17', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-17 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV145', 'Nhân viên 145', '2025-12-18', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-18 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV146', 'Nhân viên 146', '2025-12-19', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-19 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV147', 'Nhân viên 147', '2025-12-20', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-20 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV148', 'Nhân viên 148', '2025-12-21', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-21 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV149', 'Nhân viên 149', '2025-12-22', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-22 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV150', 'Nhân viên 150', '2025-12-23', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-23 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV151', 'Nhân viên 151', '2025-12-24', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-24 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV152', 'Nhân viên 152', '2025-12-25', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-25 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV153', 'Nhân viên 153', '2025-12-26', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-26 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV154', 'Nhân viên 154', '2025-12-27', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-27 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV155', 'Nhân viên 155', '2025-12-01', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-01 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV156', 'Nhân viên 156', '2025-12-02', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-02 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV157', 'Nhân viên 157', '2025-12-03', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV158', 'Nhân viên 158', '2025-12-04', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV159', 'Nhân viên 159', '2025-12-05', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV160', 'Nhân viên 160', '2025-12-06', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-06 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV161', 'Nhân viên 161', '2025-12-07', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV162', 'Nhân viên 162', '2025-12-08', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV163', 'Nhân viên 163', '2025-12-09', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV164', 'Nhân viên 164', '2025-12-10', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-10 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV165', 'Nhân viên 165', '2025-12-11', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-11 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV166', 'Nhân viên 166', '2025-12-12', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV167', 'Nhân viên 167', '2025-12-13', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV168', 'Nhân viên 168', '2025-12-14', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV169', 'Nhân viên 169', '2025-12-15', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-15 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV170', 'Nhân viên 170', '2025-12-16', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-16 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV171', 'Nhân viên 171', '2025-12-17', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-17 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV172', 'Nhân viên 172', '2025-12-18', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-18 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV173', 'Nhân viên 173', '2025-12-19', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-19 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV174', 'Nhân viên 174', '2025-12-20', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-20 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV175', 'Nhân viên 175', '2025-12-21', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-21 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV176', 'Nhân viên 176', '2025-12-22', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-22 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV177', 'Nhân viên 177', '2025-12-23', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-23 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV178', 'Nhân viên 178', '2025-12-24', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-24 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV179', 'Nhân viên 179', '2025-12-25', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-25 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV180', 'Nhân viên 180', '2025-12-26', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-26 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV181', 'Nhân viên 181', '2025-12-27', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-27 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV182', 'Nhân viên 182', '2025-12-01', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV183', 'Nhân viên 183', '2025-12-02', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-02 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV184', 'Nhân viên 184', '2025-12-03', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-03 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV185', 'Nhân viên 185', '2025-12-04', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-04 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV186', 'Nhân viên 186', '2025-12-05', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV187', 'Nhân viên 187', '2025-12-06', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV188', 'Nhân viên 188', '2025-12-07', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV189', 'Nhân viên 189', '2025-12-08', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-08 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV190', 'Nhân viên 190', '2025-12-09', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-09 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV191', 'Nhân viên 191', '2025-12-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV192', 'Nhân viên 192', '2025-12-11', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV193', 'Nhân viên 193', '2025-12-12', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV194', 'Nhân viên 194', '2025-12-13', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-13 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV195', 'Nhân viên 195', '2025-12-14', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-14 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV196', 'Nhân viên 196', '2025-12-15', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV197', 'Nhân viên 197', '2025-12-16', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-16 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV198', 'Nhân viên 198', '2025-12-17', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-17 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV199', 'Nhân viên 199', '2025-12-18', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-18 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV200', 'Nhân viên 200', '2025-12-19', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-19 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV201', 'Nhân viên 201', '2025-12-20', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-20 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV202', 'Nhân viên 202', '2025-12-21', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-21 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV203', 'Nhân viên 203', '2025-12-22', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-22 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV204', 'Nhân viên 204', '2025-12-23', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-23 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV205', 'Nhân viên 205', '2025-12-24', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-24 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV206', 'Nhân viên 206', '2025-12-25', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-25 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV207', 'Nhân viên 207', '2025-12-26', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-26 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV208', 'Nhân viên 208', '2025-12-27', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-27 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV209', 'Nhân viên 209', '2025-12-01', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-01 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV210', 'Nhân viên 210', '2025-12-02', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-02 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV211', 'Nhân viên 211', '2025-12-03', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV212', 'Nhân viên 212', '2025-12-04', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV213', 'Nhân viên 213', '2025-12-05', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV214', 'Nhân viên 214', '2025-12-06', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-06 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV215', 'Nhân viên 215', '2025-12-07', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-07 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV216', 'Nhân viên 216', '2025-12-08', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV217', 'Nhân viên 217', '2025-12-09', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV218', 'Nhân viên 218', '2025-12-10', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV219', 'Nhân viên 219', '2025-12-11', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-11 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV220', 'Nhân viên 220', '2025-12-12', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-12 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV221', 'Nhân viên 221', '2025-12-13', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV222', 'Nhân viên 222', '2025-12-14', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV223', 'Nhân viên 223', '2025-12-15', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV224', 'Nhân viên 224', '2025-12-16', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-16 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV225', 'Nhân viên 225', '2025-12-17', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-17 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV226', 'Nhân viên 226', '2025-12-18', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-18 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV227', 'Nhân viên 227', '2025-12-19', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-19 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV228', 'Nhân viên 228', '2025-12-20', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-20 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV229', 'Nhân viên 229', '2025-12-21', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-21 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV230', 'Nhân viên 230', '2025-12-22', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-22 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV231', 'Nhân viên 231', '2025-12-23', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-23 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV232', 'Nhân viên 232', '2025-12-24', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-24 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV233', 'Nhân viên 233', '2025-12-25', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-25 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV234', 'Nhân viên 234', '2025-12-26', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-26 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV235', 'Nhân viên 235', '2025-12-27', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-27 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV236', 'Nhân viên 236', '2025-12-01', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV237', 'Nhân viên 237', '2025-12-02', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-02 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV238', 'Nhân viên 238', '2025-12-03', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV239', 'Nhân viên 239', '2025-12-04', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-04 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV240', 'Nhân viên 240', '2025-12-05', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-05 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV241', 'Nhân viên 241', '2025-12-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV242', 'Nhân viên 242', '2025-12-07', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV243', 'Nhân viên 243', '2025-12-08', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV244', 'Nhân viên 244', '2025-12-09', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-09 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV245', 'Nhân viên 245', '2025-12-10', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-10 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV246', 'Nhân viên 246', '2025-12-11', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV247', 'Nhân viên 247', '2025-12-12', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-12 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV248', 'Nhân viên 248', '2025-12-13', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-13 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV249', 'Nhân viên 249', '2025-12-14', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-14 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV250', 'Nhân viên 250', '2025-12-15', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-15 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV251', 'Nhân viên 251', '2025-12-16', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-16 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV252', 'Nhân viên 252', '2025-12-17', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-17 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV253', 'Nhân viên 253', '2025-12-18', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-18 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV254', 'Nhân viên 254', '2025-12-19', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-19 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV255', 'Nhân viên 255', '2025-12-20', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-20 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV256', 'Nhân viên 256', '2025-12-21', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-21 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV257', 'Nhân viên 257', '2025-12-22', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-22 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV258', 'Nhân viên 258', '2025-12-23', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-23 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV259', 'Nhân viên 259', '2025-12-24', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-24 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV260', 'Nhân viên 260', '2025-12-25', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-25 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV261', 'Nhân viên 261', '2025-12-26', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-26 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV262', 'Nhân viên 262', '2025-12-27', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-27 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV263', 'Nhân viên 263', '2025-12-01', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-01 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV264', 'Nhân viên 264', '2025-12-02', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-02 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV265', 'Nhân viên 265', '2025-12-03', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-03 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV266', 'Nhân viên 266', '2025-12-04', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV267', 'Nhân viên 267', '2025-12-05', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-05 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV268', 'Nhân viên 268', '2025-12-06', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-06 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV269', 'Nhân viên 269', '2025-12-07', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-07 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV270', 'Nhân viên 270', '2025-12-08', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-08 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV271', 'Nhân viên 271', '2025-12-09', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV272', 'Nhân viên 272', '2025-12-10', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-10 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV273', 'Nhân viên 273', '2025-12-11', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-11 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV274', 'Nhân viên 274', '2025-12-12', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-12 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV275', 'Nhân viên 275', '2025-12-13', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-13 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV276', 'Nhân viên 276', '2025-12-14', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-14 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV277', 'Nhân viên 277', '2025-12-15', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-15 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV278', 'Nhân viên 278', '2025-12-16', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-16 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV279', 'Nhân viên 279', '2025-12-17', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-17 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV280', 'Nhân viên 280', '2025-12-18', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-18 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV281', 'Nhân viên 281', '2025-12-19', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-19 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV282', 'Nhân viên 282', '2025-12-20', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-20 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV283', 'Nhân viên 283', '2025-12-21', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-21 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV284', 'Nhân viên 284', '2025-12-22', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-22 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV285', 'Nhân viên 285', '2025-12-23', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-23 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV286', 'Nhân viên 286', '2025-12-24', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-24 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV287', 'Nhân viên 287', '2025-12-25', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-25 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV288', 'Nhân viên 288', '2025-12-26', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-26 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV289', 'Nhân viên 289', '2025-12-27', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-27 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV290', 'Nhân viên 290', '2025-12-01', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-01 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV291', 'Nhân viên 291', '2025-12-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-02 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_SANXUAT'),
('NV292', 'Nhân viên 292', '2025-12-03', 1, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-03 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_KHO'),
('NV293', 'Nhân viên 293', '2025-12-04', 0, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-04 08:00:00', NULL, 'XUONG01', 'VT_KIEM_SOAT_CL'),
('NV294', 'Nhân viên 294', '2025-12-05', 1, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-05 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV295', 'Nhân viên 295', '2025-12-06', 0, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-06 08:00:00', NULL, NULL, 'VT_KETOAN'),
('NV296', 'Nhân viên 296', '2025-12-07', 1, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Bình Dương', '2025-12-07 08:00:00', NULL, 'XUONG02', 'VT_NHANVIEN_SANXUAT'),
('NV297', 'Nhân viên 297', '2025-12-08', 0, 'Nhân viên kho', 3, 'Đang làm việc', 'Bình Dương', '2025-12-08 08:00:00', NULL, 'XUONG01', 'VT_NHANVIEN_KHO'),
('NV298', 'Nhân viên 298', '2025-12-09', 1, 'Kiểm soát chất lượng', 3, 'Đang làm việc', 'Bình Dương', '2025-12-09 08:00:00', NULL, 'XUONG02', 'VT_KIEM_SOAT_CL'),
('NV299', 'Nhân viên 299', '2025-12-10', 0, 'Nhân viên kinh doanh', 3, 'Đang làm việc', 'TP.HCM', '2025-12-10 08:00:00', NULL, NULL, 'VT_KINH_DOANH'),
('NV300', 'Nhân viên 300', '2025-12-11', 1, 'Kế toán tổng hợp', 3, 'Đang làm việc', 'TP.HCM', '2025-12-11 08:00:00', NULL, NULL, 'VT_KETOAN');

INSERT INTO `xuong_nhan_vien` (`IdXuong`, `IdNhanVien`, `VaiTro`) VALUES
('XUONG01', 'NV_XU01', 'Xưởng trưởng'),
('XUONG01', 'NV_QC01', 'Kiểm soát chất lượng'),
('XUONG01', 'NV_SX01', 'Nhân viên sản xuất'),
('XUONG01', 'NV_KNV01', 'Nhân viên kho'),
('XUONG02', 'NV_XU02', 'Xưởng trưởng'),
('XUONG02', 'NV_SX02', 'Nhân viên sản xuất'),
('XUONG02', 'NV_KHO02', 'Kho trưởng'),
('XUONG03', 'NV_XU03', 'Xưởng trưởng'),
('XUONG03', 'NV_KHO03', 'Kho trưởng'),
('XUONG04', 'NV_XU04', 'Xưởng trưởng'),
('XUONG04', 'NV_KHO04', 'Kho trưởng'),
('XUONG05', 'NV_XU05', 'Xưởng trưởng'),
('XUONG05', 'NV_KHO05', 'Kho trưởng'),
('XUONG06', 'NV_XU06', 'Xưởng trưởng'),
('XUONG06', 'NV_KHO06', 'Kho trưởng'),
('XUONG07', 'NV_XU07', 'Xưởng trưởng'),
('XUONG07', 'NV_KHO07', 'Kho trưởng'),
('XUONG08', 'NV_XU08', 'Xưởng trưởng'),
('XUONG08', 'NV_KHO08', 'Kho trưởng'),
('XUONG09', 'NV_XU09', 'Xưởng trưởng'),
('XUONG09', 'NV_KHO09', 'Kho trưởng'),
('XUONG10', 'NV_XU10', 'Xưởng trưởng'),
('XUONG10', 'NV_KHO10', 'Kho trưởng'),
('XUONG11', 'NV_XU11', 'Xưởng trưởng'),
('XUONG11', 'NV_KHO11', 'Kho trưởng'),
('XUONG12', 'NV_XU12', 'Xưởng trưởng'),
('XUONG12', 'NV_KHO12', 'Kho trưởng'),
('XUONG13', 'NV_XU13', 'Xưởng trưởng'),
('XUONG13', 'NV_KHO13', 'Kho trưởng'),
('XUONG14', 'NV_XU14', 'Xưởng trưởng'),
('XUONG14', 'NV_KHO14', 'Kho trưởng');

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
('NV_KD01', 'nv_kd01', '123456', 'Hoạt động', 'NV_KD01', 'VT_KINH_DOANH'),
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
































SET FOREIGN_KEY_CHECKS=1;
