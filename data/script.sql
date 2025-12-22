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
  `LOIdLo` varchar(50) NOT NULL
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
  `IdKhachHang` varchar(50) NOT NULL
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
  `idXuong` varchar(50) DEFAULT NULL
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

-- (Dữ liệu đã được lược bỏ)

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
-- Dữ liệu mẫu cho bảng `nhan_vien`
--
SET FOREIGN_KEY_CHECKS=0;
INSERT INTO `nhan_vien` (`IdNhanVien`, `HoTen`, `NgaySinh`, `GioiTinh`, `ChucVu`, `HeSoLuong`, `TrangThai`, `DiaChi`, `ThoiGianLamViec`, `ChuKy`, `idXuong`) VALUES
('NV001', 'Nhân viên 001', '1981-02-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 001, Bình Dương', '2016-02-02 08:00:00', NULL, NULL),
('NV002', 'Nhân viên 002', '1982-03-03', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 002, Bình Dương', '2017-03-03 08:00:00', NULL, NULL),
('NV003', 'Nhân viên 003', '1983-04-04', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 003, Bình Dương', '2018-04-04 08:00:00', NULL, NULL),
('NV004', 'Nhân viên 004', '1984-05-05', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 004, Bình Dương', '2019-05-05 08:00:00', NULL, NULL),
('NV005', 'Nhân viên 005', '1985-06-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 005, Bình Dương', '2020-06-06 08:00:00', NULL, NULL),
('NV006', 'Nhân viên 006', '1986-07-07', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 006, Bình Dương', '2021-07-07 08:00:00', NULL, NULL),
('NV007', 'Nhân viên 007', '1987-08-08', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 007, Bình Dương', '2022-08-08 08:00:00', NULL, NULL),
('NV008', 'Nhân viên 008', '1988-09-09', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 008, Bình Dương', '2015-09-09 08:00:00', NULL, NULL),
('NV009', 'Nhân viên 009', '1989-10-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 009, Bình Dương', '2016-10-10 08:00:00', NULL, NULL),
('NV010', 'Nhân viên 010', '1990-11-11', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 010, Bình Dương', '2017-11-11 08:00:00', NULL, NULL),
('NV011', 'Nhân viên 011', '1991-12-12', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 011, Bình Dương', '2018-12-12 08:00:00', NULL, NULL),
('NV012', 'Nhân viên 012', '1992-01-13', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 012, Bình Dương', '2019-01-13 08:00:00', NULL, NULL),
('NV013', 'Nhân viên 013', '1993-02-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 013, Bình Dương', '2020-02-14 08:00:00', NULL, NULL),
('NV014', 'Nhân viên 014', '1994-03-15', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 014, Bình Dương', '2021-03-15 08:00:00', NULL, NULL),
('NV015', 'Nhân viên 015', '1995-04-16', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 015, Bình Dương', '2022-04-16 08:00:00', NULL, NULL),
('NV016', 'Nhân viên 016', '1996-05-17', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 016, Bình Dương', '2015-05-17 08:00:00', NULL, NULL),
('NV017', 'Nhân viên 017', '1997-06-18', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 017, Bình Dương', '2016-06-18 08:00:00', NULL, NULL),
('NV018', 'Nhân viên 018', '1998-07-19', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 018, Bình Dương', '2017-07-19 08:00:00', NULL, NULL),
('NV019', 'Nhân viên 019', '1999-08-20', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 019, Bình Dương', '2018-08-20 08:00:00', NULL, NULL),
('NV020', 'Nhân viên 020', '1980-09-21', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 020, Bình Dương', '2019-09-21 08:00:00', NULL, NULL),
('NV021', 'Nhân viên 021', '1981-10-22', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 021, Bình Dương', '2020-10-22 08:00:00', NULL, NULL),
('NV022', 'Nhân viên 022', '1982-11-23', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 022, Bình Dương', '2021-11-23 08:00:00', NULL, NULL),
('NV023', 'Nhân viên 023', '1983-12-24', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 023, Bình Dương', '2022-12-24 08:00:00', NULL, NULL),
('NV024', 'Nhân viên 024', '1984-01-25', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 024, Bình Dương', '2015-01-25 08:00:00', NULL, NULL),
('NV025', 'Nhân viên 025', '1985-02-26', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 025, Bình Dương', '2016-02-26 08:00:00', NULL, NULL),
('NV026', 'Nhân viên 026', '1986-03-27', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 026, Bình Dương', '2017-03-27 08:00:00', NULL, NULL),
('NV027', 'Nhân viên 027', '1987-04-28', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 027, Bình Dương', '2018-04-28 08:00:00', NULL, NULL),
('NV028', 'Nhân viên 028', '1988-05-01', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 028, Bình Dương', '2019-05-01 08:00:00', NULL, NULL),
('NV029', 'Nhân viên 029', '1989-06-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 029, Bình Dương', '2020-06-02 08:00:00', NULL, NULL),
('NV030', 'Nhân viên 030', '1990-07-03', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 030, Bình Dương', '2021-07-03 08:00:00', NULL, NULL),
('NV031', 'Nhân viên 031', '1991-08-04', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 031, Bình Dương', '2022-08-04 08:00:00', NULL, NULL),
('NV032', 'Nhân viên 032', '1992-09-05', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 032, Bình Dương', '2015-09-05 08:00:00', NULL, NULL),
('NV033', 'Nhân viên 033', '1993-10-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 033, Bình Dương', '2016-10-06 08:00:00', NULL, NULL),
('NV034', 'Nhân viên 034', '1994-11-07', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 034, Bình Dương', '2017-11-07 08:00:00', NULL, NULL),
('NV035', 'Nhân viên 035', '1995-12-08', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 035, Bình Dương', '2018-12-08 08:00:00', NULL, NULL),
('NV036', 'Nhân viên 036', '1996-01-09', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 036, Bình Dương', '2019-01-09 08:00:00', NULL, NULL),
('NV037', 'Nhân viên 037', '1997-02-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 037, Bình Dương', '2020-02-10 08:00:00', NULL, NULL),
('NV038', 'Nhân viên 038', '1998-03-11', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 038, Bình Dương', '2021-03-11 08:00:00', NULL, NULL),
('NV039', 'Nhân viên 039', '1999-04-12', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 039, Bình Dương', '2022-04-12 08:00:00', NULL, NULL),
('NV040', 'Nhân viên 040', '1980-05-13', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 040, Bình Dương', '2015-05-13 08:00:00', NULL, NULL),
('NV041', 'Nhân viên 041', '1981-06-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 041, Bình Dương', '2016-06-14 08:00:00', NULL, NULL),
('NV042', 'Nhân viên 042', '1982-07-15', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 042, Bình Dương', '2017-07-15 08:00:00', NULL, NULL),
('NV043', 'Nhân viên 043', '1983-08-16', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 043, Bình Dương', '2018-08-16 08:00:00', NULL, NULL),
('NV044', 'Nhân viên 044', '1984-09-17', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 044, Bình Dương', '2019-09-17 08:00:00', NULL, NULL),
('NV045', 'Nhân viên 045', '1985-10-18', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 045, Bình Dương', '2020-10-18 08:00:00', NULL, NULL),
('NV046', 'Nhân viên 046', '1986-11-19', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 046, Bình Dương', '2021-11-19 08:00:00', NULL, NULL),
('NV047', 'Nhân viên 047', '1987-12-20', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 047, Bình Dương', '2022-12-20 08:00:00', NULL, NULL),
('NV048', 'Nhân viên 048', '1988-01-21', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 048, Bình Dương', '2015-01-21 08:00:00', NULL, NULL),
('NV049', 'Nhân viên 049', '1989-02-22', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 049, Bình Dương', '2016-02-22 08:00:00', NULL, NULL),
('NV050', 'Nhân viên 050', '1990-03-23', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 050, Bình Dương', '2017-03-23 08:00:00', NULL, NULL),
('NV051', 'Nhân viên 051', '1991-04-24', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 051, Bình Dương', '2018-04-24 08:00:00', NULL, NULL),
('NV052', 'Nhân viên 052', '1992-05-25', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 052, Bình Dương', '2019-05-25 08:00:00', NULL, NULL),
('NV053', 'Nhân viên 053', '1993-06-26', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 053, Bình Dương', '2020-06-26 08:00:00', NULL, NULL),
('NV054', 'Nhân viên 054', '1994-07-27', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 054, Bình Dương', '2021-07-27 08:00:00', NULL, NULL),
('NV055', 'Nhân viên 055', '1995-08-28', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 055, Bình Dương', '2022-08-28 08:00:00', NULL, NULL),
('NV056', 'Nhân viên 056', '1996-09-01', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 056, Bình Dương', '2015-09-01 08:00:00', NULL, NULL),
('NV057', 'Nhân viên 057', '1997-10-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 057, Bình Dương', '2016-10-02 08:00:00', NULL, NULL),
('NV058', 'Nhân viên 058', '1998-11-03', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 058, Bình Dương', '2017-11-03 08:00:00', NULL, NULL),
('NV059', 'Nhân viên 059', '1999-12-04', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 059, Bình Dương', '2018-12-04 08:00:00', NULL, NULL),
('NV060', 'Nhân viên 060', '1980-01-05', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 060, Bình Dương', '2019-01-05 08:00:00', NULL, NULL),
('NV061', 'Nhân viên 061', '1981-02-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 061, Bình Dương', '2020-02-06 08:00:00', NULL, NULL),
('NV062', 'Nhân viên 062', '1982-03-07', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 062, Bình Dương', '2021-03-07 08:00:00', NULL, NULL),
('NV063', 'Nhân viên 063', '1983-04-08', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 063, Bình Dương', '2022-04-08 08:00:00', NULL, NULL),
('NV064', 'Nhân viên 064', '1984-05-09', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 064, Bình Dương', '2015-05-09 08:00:00', NULL, NULL),
('NV065', 'Nhân viên 065', '1985-06-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 065, Bình Dương', '2016-06-10 08:00:00', NULL, NULL),
('NV066', 'Nhân viên 066', '1986-07-11', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 066, Bình Dương', '2017-07-11 08:00:00', NULL, NULL),
('NV067', 'Nhân viên 067', '1987-08-12', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 067, Bình Dương', '2018-08-12 08:00:00', NULL, NULL),
('NV068', 'Nhân viên 068', '1988-09-13', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 068, Bình Dương', '2019-09-13 08:00:00', NULL, NULL),
('NV069', 'Nhân viên 069', '1989-10-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 069, Bình Dương', '2020-10-14 08:00:00', NULL, NULL),
('NV070', 'Nhân viên 070', '1990-11-15', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 070, Bình Dương', '2021-11-15 08:00:00', NULL, NULL),
('NV071', 'Nhân viên 071', '1991-12-16', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 071, Bình Dương', '2022-12-16 08:00:00', NULL, NULL),
('NV072', 'Nhân viên 072', '1992-01-17', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 072, Bình Dương', '2015-01-17 08:00:00', NULL, NULL),
('NV073', 'Nhân viên 073', '1993-02-18', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 073, Bình Dương', '2016-02-18 08:00:00', NULL, NULL),
('NV074', 'Nhân viên 074', '1994-03-19', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 074, Bình Dương', '2017-03-19 08:00:00', NULL, NULL),
('NV075', 'Nhân viên 075', '1995-04-20', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 075, Bình Dương', '2018-04-20 08:00:00', NULL, NULL),
('NV076', 'Nhân viên 076', '1996-05-21', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 076, Bình Dương', '2019-05-21 08:00:00', NULL, NULL),
('NV077', 'Nhân viên 077', '1997-06-22', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 077, Bình Dương', '2020-06-22 08:00:00', NULL, NULL),
('NV078', 'Nhân viên 078', '1998-07-23', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 078, Bình Dương', '2021-07-23 08:00:00', NULL, NULL),
('NV079', 'Nhân viên 079', '1999-08-24', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 079, Bình Dương', '2022-08-24 08:00:00', NULL, NULL),
('NV080', 'Nhân viên 080', '1980-09-25', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 080, Bình Dương', '2015-09-25 08:00:00', NULL, NULL),
('NV081', 'Nhân viên 081', '1981-10-26', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 081, Bình Dương', '2016-10-26 08:00:00', NULL, NULL),
('NV082', 'Nhân viên 082', '1982-11-27', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 082, Bình Dương', '2017-11-27 08:00:00', NULL, NULL),
('NV083', 'Nhân viên 083', '1983-12-28', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 083, Bình Dương', '2018-12-28 08:00:00', NULL, NULL),
('NV084', 'Nhân viên 084', '1984-01-01', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 084, Bình Dương', '2019-01-01 08:00:00', NULL, NULL),
('NV085', 'Nhân viên 085', '1985-02-02', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 085, Bình Dương', '2020-02-02 08:00:00', NULL, NULL),
('NV086', 'Nhân viên 086', '1986-03-03', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 086, Bình Dương', '2021-03-03 08:00:00', NULL, NULL),
('NV087', 'Nhân viên 087', '1987-04-04', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 087, Bình Dương', '2022-04-04 08:00:00', NULL, NULL),
('NV088', 'Nhân viên 088', '1988-05-05', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 088, Bình Dương', '2015-05-05 08:00:00', NULL, NULL),
('NV089', 'Nhân viên 089', '1989-06-06', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 089, Bình Dương', '2016-06-06 08:00:00', NULL, NULL),
('NV090', 'Nhân viên 090', '1990-07-07', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 090, Bình Dương', '2017-07-07 08:00:00', NULL, NULL),
('NV091', 'Nhân viên 091', '1991-08-08', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 091, Bình Dương', '2018-08-08 08:00:00', NULL, NULL),
('NV092', 'Nhân viên 092', '1992-09-09', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 092, Bình Dương', '2019-09-09 08:00:00', NULL, NULL),
('NV093', 'Nhân viên 093', '1993-10-10', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 093, Bình Dương', '2020-10-10 08:00:00', NULL, NULL),
('NV094', 'Nhân viên 094', '1994-11-11', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 094, Bình Dương', '2021-11-11 08:00:00', NULL, NULL),
('NV095', 'Nhân viên 095', '1995-12-12', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 095, Bình Dương', '2022-12-12 08:00:00', NULL, NULL),
('NV096', 'Nhân viên 096', '1996-01-13', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 096, Bình Dương', '2015-01-13 08:00:00', NULL, NULL),
('NV097', 'Nhân viên 097', '1997-02-14', 0, 'Nhân viên sản xuất', 3, 'Đang làm việc', 'Khu vực 097, Bình Dương', '2016-02-14 08:00:00', NULL, NULL),
('NV098', 'Nhân viên 098', '1998-03-15', 1, 'Nhân viên sản xuất', 4, 'Đang làm việc', 'Khu vực 098, Bình Dương', '2017-03-15 08:00:00', NULL, NULL),
('NV099', 'Nhân viên 099', '1999-04-16', 0, 'Nhân viên sản xuất', 5, 'Đang làm việc', 'Khu vực 099, Bình Dương', '2018-04-16 08:00:00', NULL, NULL),
('NV100', 'Nhân viên 100', '1980-05-17', 1, 'Nhân viên sản xuất', 2, 'Đang làm việc', 'Khu vực 100, Bình Dương', '2019-05-17 08:00:00', NULL, NULL);

--
-- Dữ liệu mẫu cho bảng `nguoi_dung`
--
INSERT INTO `nguoi_dung` (`IdNguoiDung`, `TenDangNhap`, `MatKhau`, `TrangThai`, `IdNhanVien`, `IdVaiTro`) VALUES
('ND001', 'user001', 'matkhau@123', 'Hoạt động', 'NV001', 'VT_ADMIN'),
('ND002', 'user002', 'matkhau@123', 'Hoạt động', 'NV002', 'VT_BAN_GIAM_DOC'),
('ND003', 'user003', 'matkhau@123', 'Hoạt động', 'NV003', 'VT_DOI_TAC_VAN_TAI'),
('ND004', 'user004', 'matkhau@123', 'Hoạt động', 'NV004', 'VT_KETOAN'),
('ND005', 'user005', 'matkhau@123', 'Hoạt động', 'NV005', 'VT_KHACH'),
('ND006', 'user006', 'matkhau@123', 'Hoạt động', 'NV006', 'VT_KIEM_SOAT_CL'),
('ND007', 'user007', 'matkhau@123', 'Hoạt động', 'NV007', 'VT_KINH_DOANH'),
('ND008', 'user008', 'matkhau@123', 'Hoạt động', 'NV008', 'VT_NHANVIEN_KHO'),
('ND009', 'user009', 'matkhau@123', 'Hoạt động', 'NV009', 'VT_NHANVIEN_SANXUAT'),
('ND010', 'user010', 'matkhau@123', 'Hoạt động', 'NV010', 'VT_NHAN_SU'),
('ND011', 'user011', 'matkhau@123', 'Hoạt động', 'NV011', 'VT_QUANLY_XUONG'),
('ND012', 'user012', 'matkhau@123', 'Hoạt động', 'NV012', 'VT_ADMIN'),
('ND013', 'user013', 'matkhau@123', 'Hoạt động', 'NV013', 'VT_BAN_GIAM_DOC'),
('ND014', 'user014', 'matkhau@123', 'Hoạt động', 'NV014', 'VT_DOI_TAC_VAN_TAI'),
('ND015', 'user015', 'matkhau@123', 'Hoạt động', 'NV015', 'VT_KETOAN'),
('ND016', 'user016', 'matkhau@123', 'Hoạt động', 'NV016', 'VT_KHACH'),
('ND017', 'user017', 'matkhau@123', 'Hoạt động', 'NV017', 'VT_KIEM_SOAT_CL'),
('ND018', 'user018', 'matkhau@123', 'Hoạt động', 'NV018', 'VT_KINH_DOANH'),
('ND019', 'user019', 'matkhau@123', 'Hoạt động', 'NV019', 'VT_NHANVIEN_KHO'),
('ND020', 'user020', 'matkhau@123', 'Hoạt động', 'NV020', 'VT_NHANVIEN_SANXUAT'),
('ND021', 'user021', 'matkhau@123', 'Hoạt động', 'NV021', 'VT_NHAN_SU'),
('ND022', 'user022', 'matkhau@123', 'Hoạt động', 'NV022', 'VT_QUANLY_XUONG'),
('ND023', 'user023', 'matkhau@123', 'Hoạt động', 'NV023', 'VT_ADMIN'),
('ND024', 'user024', 'matkhau@123', 'Hoạt động', 'NV024', 'VT_BAN_GIAM_DOC'),
('ND025', 'user025', 'matkhau@123', 'Hoạt động', 'NV025', 'VT_DOI_TAC_VAN_TAI'),
('ND026', 'user026', 'matkhau@123', 'Hoạt động', 'NV026', 'VT_KETOAN'),
('ND027', 'user027', 'matkhau@123', 'Hoạt động', 'NV027', 'VT_KHACH'),
('ND028', 'user028', 'matkhau@123', 'Hoạt động', 'NV028', 'VT_KIEM_SOAT_CL'),
('ND029', 'user029', 'matkhau@123', 'Hoạt động', 'NV029', 'VT_KINH_DOANH'),
('ND030', 'user030', 'matkhau@123', 'Hoạt động', 'NV030', 'VT_NHANVIEN_KHO'),
('ND031', 'user031', 'matkhau@123', 'Hoạt động', 'NV031', 'VT_NHANVIEN_SANXUAT'),
('ND032', 'user032', 'matkhau@123', 'Hoạt động', 'NV032', 'VT_NHAN_SU'),
('ND033', 'user033', 'matkhau@123', 'Hoạt động', 'NV033', 'VT_QUANLY_XUONG'),
('ND034', 'user034', 'matkhau@123', 'Hoạt động', 'NV034', 'VT_ADMIN'),
('ND035', 'user035', 'matkhau@123', 'Hoạt động', 'NV035', 'VT_BAN_GIAM_DOC'),
('ND036', 'user036', 'matkhau@123', 'Hoạt động', 'NV036', 'VT_DOI_TAC_VAN_TAI'),
('ND037', 'user037', 'matkhau@123', 'Hoạt động', 'NV037', 'VT_KETOAN'),
('ND038', 'user038', 'matkhau@123', 'Hoạt động', 'NV038', 'VT_KHACH'),
('ND039', 'user039', 'matkhau@123', 'Hoạt động', 'NV039', 'VT_KIEM_SOAT_CL'),
('ND040', 'user040', 'matkhau@123', 'Hoạt động', 'NV040', 'VT_KINH_DOANH'),
('ND041', 'user041', 'matkhau@123', 'Hoạt động', 'NV041', 'VT_NHANVIEN_KHO'),
('ND042', 'user042', 'matkhau@123', 'Hoạt động', 'NV042', 'VT_NHANVIEN_SANXUAT'),
('ND043', 'user043', 'matkhau@123', 'Hoạt động', 'NV043', 'VT_NHAN_SU'),
('ND044', 'user044', 'matkhau@123', 'Hoạt động', 'NV044', 'VT_QUANLY_XUONG'),
('ND045', 'user045', 'matkhau@123', 'Hoạt động', 'NV045', 'VT_ADMIN'),
('ND046', 'user046', 'matkhau@123', 'Hoạt động', 'NV046', 'VT_BAN_GIAM_DOC'),
('ND047', 'user047', 'matkhau@123', 'Hoạt động', 'NV047', 'VT_DOI_TAC_VAN_TAI'),
('ND048', 'user048', 'matkhau@123', 'Hoạt động', 'NV048', 'VT_KETOAN'),
('ND049', 'user049', 'matkhau@123', 'Hoạt động', 'NV049', 'VT_KHACH'),
('ND050', 'user050', 'matkhau@123', 'Hoạt động', 'NV050', 'VT_KIEM_SOAT_CL'),
('ND051', 'user051', 'matkhau@123', 'Hoạt động', 'NV051', 'VT_KINH_DOANH'),
('ND052', 'user052', 'matkhau@123', 'Hoạt động', 'NV052', 'VT_NHANVIEN_KHO'),
('ND053', 'user053', 'matkhau@123', 'Hoạt động', 'NV053', 'VT_NHANVIEN_SANXUAT'),
('ND054', 'user054', 'matkhau@123', 'Hoạt động', 'NV054', 'VT_NHAN_SU'),
('ND055', 'user055', 'matkhau@123', 'Hoạt động', 'NV055', 'VT_QUANLY_XUONG'),
('ND056', 'user056', 'matkhau@123', 'Hoạt động', 'NV056', 'VT_ADMIN'),
('ND057', 'user057', 'matkhau@123', 'Hoạt động', 'NV057', 'VT_BAN_GIAM_DOC'),
('ND058', 'user058', 'matkhau@123', 'Hoạt động', 'NV058', 'VT_DOI_TAC_VAN_TAI'),
('ND059', 'user059', 'matkhau@123', 'Hoạt động', 'NV059', 'VT_KETOAN'),
('ND060', 'user060', 'matkhau@123', 'Hoạt động', 'NV060', 'VT_KHACH'),
('ND061', 'user061', 'matkhau@123', 'Hoạt động', 'NV061', 'VT_KIEM_SOAT_CL'),
('ND062', 'user062', 'matkhau@123', 'Hoạt động', 'NV062', 'VT_KINH_DOANH'),
('ND063', 'user063', 'matkhau@123', 'Hoạt động', 'NV063', 'VT_NHANVIEN_KHO'),
('ND064', 'user064', 'matkhau@123', 'Hoạt động', 'NV064', 'VT_NHANVIEN_SANXUAT'),
('ND065', 'user065', 'matkhau@123', 'Hoạt động', 'NV065', 'VT_NHAN_SU'),
('ND066', 'user066', 'matkhau@123', 'Hoạt động', 'NV066', 'VT_QUANLY_XUONG'),
('ND067', 'user067', 'matkhau@123', 'Hoạt động', 'NV067', 'VT_ADMIN'),
('ND068', 'user068', 'matkhau@123', 'Hoạt động', 'NV068', 'VT_BAN_GIAM_DOC'),
('ND069', 'user069', 'matkhau@123', 'Hoạt động', 'NV069', 'VT_DOI_TAC_VAN_TAI'),
('ND070', 'user070', 'matkhau@123', 'Hoạt động', 'NV070', 'VT_KETOAN'),
('ND071', 'user071', 'matkhau@123', 'Hoạt động', 'NV071', 'VT_KHACH'),
('ND072', 'user072', 'matkhau@123', 'Hoạt động', 'NV072', 'VT_KIEM_SOAT_CL'),
('ND073', 'user073', 'matkhau@123', 'Hoạt động', 'NV073', 'VT_KINH_DOANH'),
('ND074', 'user074', 'matkhau@123', 'Hoạt động', 'NV074', 'VT_NHANVIEN_KHO'),
('ND075', 'user075', 'matkhau@123', 'Hoạt động', 'NV075', 'VT_NHANVIEN_SANXUAT'),
('ND076', 'user076', 'matkhau@123', 'Hoạt động', 'NV076', 'VT_NHAN_SU'),
('ND077', 'user077', 'matkhau@123', 'Hoạt động', 'NV077', 'VT_QUANLY_XUONG'),
('ND078', 'user078', 'matkhau@123', 'Hoạt động', 'NV078', 'VT_ADMIN'),
('ND079', 'user079', 'matkhau@123', 'Hoạt động', 'NV079', 'VT_BAN_GIAM_DOC'),
('ND080', 'user080', 'matkhau@123', 'Hoạt động', 'NV080', 'VT_DOI_TAC_VAN_TAI'),
('ND081', 'user081', 'matkhau@123', 'Hoạt động', 'NV081', 'VT_KETOAN'),
('ND082', 'user082', 'matkhau@123', 'Hoạt động', 'NV082', 'VT_KHACH'),
('ND083', 'user083', 'matkhau@123', 'Hoạt động', 'NV083', 'VT_KIEM_SOAT_CL'),
('ND084', 'user084', 'matkhau@123', 'Hoạt động', 'NV084', 'VT_KINH_DOANH'),
('ND085', 'user085', 'matkhau@123', 'Hoạt động', 'NV085', 'VT_NHANVIEN_KHO'),
('ND086', 'user086', 'matkhau@123', 'Hoạt động', 'NV086', 'VT_NHANVIEN_SANXUAT'),
('ND087', 'user087', 'matkhau@123', 'Hoạt động', 'NV087', 'VT_NHAN_SU'),
('ND088', 'user088', 'matkhau@123', 'Hoạt động', 'NV088', 'VT_QUANLY_XUONG'),
('ND089', 'user089', 'matkhau@123', 'Hoạt động', 'NV089', 'VT_ADMIN'),
('ND090', 'user090', 'matkhau@123', 'Hoạt động', 'NV090', 'VT_BAN_GIAM_DOC'),
('ND091', 'user091', 'matkhau@123', 'Hoạt động', 'NV091', 'VT_DOI_TAC_VAN_TAI'),
('ND092', 'user092', 'matkhau@123', 'Hoạt động', 'NV092', 'VT_KETOAN'),
('ND093', 'user093', 'matkhau@123', 'Hoạt động', 'NV093', 'VT_KHACH'),
('ND094', 'user094', 'matkhau@123', 'Hoạt động', 'NV094', 'VT_KIEM_SOAT_CL'),
('ND095', 'user095', 'matkhau@123', 'Hoạt động', 'NV095', 'VT_KINH_DOANH'),
('ND096', 'user096', 'matkhau@123', 'Hoạt động', 'NV096', 'VT_NHANVIEN_KHO'),
('ND097', 'user097', 'matkhau@123', 'Hoạt động', 'NV097', 'VT_NHANVIEN_SANXUAT'),
('ND098', 'user098', 'matkhau@123', 'Hoạt động', 'NV098', 'VT_NHAN_SU'),
('ND099', 'user099', 'matkhau@123', 'Hoạt động', 'NV099', 'VT_QUANLY_XUONG'),
('ND100', 'user100', 'matkhau@123', 'Hoạt động', 'NV100', 'VT_ADMIN');
SET FOREIGN_KEY_CHECKS=1;
