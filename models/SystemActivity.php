<?php

class SystemActivity extends BaseModel
{
    protected string $table = 'HOAT_DONG_HE_THONG';
    protected string $primaryKey = 'IdHoatDong';

    public function latest(int $limit = 8): array
    {
        $sql = 'SELECT * FROM HOAT_DONG_HE_THONG ORDER BY ThoiGian DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
