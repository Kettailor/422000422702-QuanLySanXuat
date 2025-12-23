<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Yêu cầu hỗ trợ</h3>
        <p class="text-muted mb-0">Gửi yêu cầu để admin xử lý nhanh các vấn đề phát sinh.</p>
    </div>
    <a href="?controller=dashboard&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card p-4">
            <form method="post" action="?controller=support&action=index" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mức độ ưu tiên</label>
                    <select name="priority" class="form-select">
                        <option value="normal">Bình thường</option>
                        <option value="high">Quan trọng</option>
                        <option value="urgent">Khẩn cấp</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Chủ đề</label>
                    <input type="text" name="subject" class="form-control" placeholder="Ví dụ: Lỗi truy cập kế hoạch xưởng">
                </div>
                <div class="col-12">
                    <label class="form-label">Nội dung chi tiết</label>
                    <textarea name="message" rows="5" class="form-control" placeholder="Mô tả rõ vấn đề, thời điểm xảy ra và thông tin liên quan."></textarea>
                </div>
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Thông tin người gửi sẽ được gửi kèm để admin tiện phản hồi.
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>Gửi yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card p-4 h-100">
            <h5 class="mb-3">Hướng dẫn nhanh</h5>
            <ul class="list-unstyled text-muted mb-0">
                <li class="mb-2"><i class="bi bi-check-circle me-2 text-primary"></i>Chọn mức độ ưu tiên phù hợp.</li>
                <li class="mb-2"><i class="bi bi-check-circle me-2 text-primary"></i>Điền tiêu đề ngắn gọn để admin dễ phân loại.</li>
                <li class="mb-2"><i class="bi bi-check-circle me-2 text-primary"></i>Mô tả chi tiết để giảm thời gian trao đổi.</li>
                <li><i class="bi bi-check-circle me-2 text-primary"></i>Bạn có thể theo dõi phản hồi từ admin sau khi gửi.</li>
            </ul>
        </div>
    </div>
</div>
