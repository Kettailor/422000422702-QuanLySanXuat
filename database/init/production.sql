CREATE TABLE IF NOT EXISTS production_lines (
    id SERIAL PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    department TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'idle'
);

CREATE TABLE IF NOT EXISTS employees (
    id SERIAL PRIMARY KEY,
    employee_code TEXT NOT NULL UNIQUE,
    full_name TEXT NOT NULL,
    title TEXT,
    system_role TEXT NOT NULL DEFAULT 'employee',
    status TEXT NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS workshops (
    id SERIAL PRIMARY KEY,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    location TEXT,
    status TEXT NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS workshop_assignments (
    id SERIAL PRIMARY KEY,
    workshop_id INTEGER NOT NULL REFERENCES workshops(id) ON DELETE CASCADE,
    employee_id INTEGER NOT NULL REFERENCES employees(id) ON DELETE CASCADE,
    assignment_role TEXT NOT NULL,
    assigned_at TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (workshop_id, employee_id),
    CONSTRAINT uq_workshop_manager UNIQUE (workshop_id, assignment_role) WHERE assignment_role = 'manager'
);

CREATE INDEX IF NOT EXISTS idx_employees_full_name ON employees USING GIN (to_tsvector('simple', full_name));
CREATE INDEX IF NOT EXISTS idx_workshop_assignments_role ON workshop_assignments (assignment_role);

CREATE TABLE IF NOT EXISTS shifts (
    id SERIAL PRIMARY KEY,
    shift_date DATE NOT NULL,
    shift_name TEXT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT uq_shift UNIQUE (shift_date, shift_name)
);

CREATE TABLE IF NOT EXISTS work_orders (
    id SERIAL PRIMARY KEY,
    order_code TEXT NOT NULL UNIQUE,
    line_id INTEGER NOT NULL REFERENCES production_lines(id),
    product_code TEXT NOT NULL,
    planned_quantity INTEGER NOT NULL,
    completed_quantity INTEGER NOT NULL DEFAULT 0,
    scrap_quantity INTEGER NOT NULL DEFAULT 0,
    status TEXT NOT NULL,
    due_time TIMESTAMP NOT NULL DEFAULT NOW(),
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS line_shift_metrics (
    id SERIAL PRIMARY KEY,
    line_id INTEGER NOT NULL REFERENCES production_lines(id),
    shift_id INTEGER NOT NULL REFERENCES shifts(id),
    planned_output INTEGER NOT NULL,
    actual_output INTEGER NOT NULL,
    downtime_minutes INTEGER NOT NULL DEFAULT 0,
    CONSTRAINT uq_line_shift UNIQUE(line_id, shift_id)
);

INSERT INTO production_lines (code, name, department, status)
VALUES
    ('L1', 'Line 1 - Assembly', 'Assembly', 'running'),
    ('L2', 'Line 2 - Packaging', 'Logistics', 'maintenance'),
    ('L3', 'Line 3 - CNC', 'Machining', 'idle')
ON CONFLICT (code) DO NOTHING;

INSERT INTO shifts (shift_date, shift_name, start_time, end_time)
VALUES
    (CURRENT_DATE, 'Morning', '06:00', '14:00'),
    (CURRENT_DATE, 'Evening', '14:00', '22:00'),
    (CURRENT_DATE, 'Night', '22:00', '06:00')
ON CONFLICT (shift_date, shift_name) DO NOTHING;

INSERT INTO employees (employee_code, full_name, title, system_role, status)
VALUES
    ('EMP-ADM', 'Nguyễn Thị An', 'Quản trị hệ thống', 'system_admin', 'active'),
    ('EMP-BOD', 'Trần Quang Huy', 'Ban giám đốc', 'board', 'active'),
    ('EMP-MGR', 'Lê Minh Phong', 'Trưởng xưởng', 'workshop_manager', 'active'),
    ('EMP-WH1', 'Đỗ Thị Hạnh', 'Nhân viên kho', 'warehouse_staff', 'active'),
    ('EMP-P01', 'Phạm Văn Khải', 'Nhân viên sản xuất', 'production_staff', 'active'),
    ('EMP-P02', 'Vũ Thị Lan', 'Nhân viên sản xuất', 'production_staff', 'active')
ON CONFLICT (employee_code) DO NOTHING;

INSERT INTO workshops (code, name, location, status)
VALUES
    ('WS-001', 'Xưởng Lắp ráp', 'Khu A', 'active'),
    ('WS-002', 'Xưởng Gia công', 'Khu B', 'active')
ON CONFLICT (code) DO NOTHING;

INSERT INTO workshop_assignments (workshop_id, employee_id, assignment_role, assigned_at)
SELECT * FROM (
    VALUES
        ((SELECT id FROM workshops WHERE code = 'WS-001'), (SELECT id FROM employees WHERE employee_code = 'EMP-MGR'), 'manager', NOW()),
        ((SELECT id FROM workshops WHERE code = 'WS-001'), (SELECT id FROM employees WHERE employee_code = 'EMP-WH1'), 'warehouse', NOW()),
        ((SELECT id FROM workshops WHERE code = 'WS-001'), (SELECT id FROM employees WHERE employee_code = 'EMP-P01'), 'production', NOW()),
        ((SELECT id FROM workshops WHERE code = 'WS-002'), (SELECT id FROM employees WHERE employee_code = 'EMP-P02'), 'production', NOW())
) AS seed(workshop_id, employee_id, assignment_role, assigned_at)
WHERE seed.workshop_id IS NOT NULL AND seed.employee_id IS NOT NULL
ON CONFLICT DO NOTHING;

INSERT INTO work_orders (order_code, line_id, product_code, planned_quantity, completed_quantity, scrap_quantity, status, due_time)
SELECT * FROM (
    VALUES
        ('WO-001', 1, 'P-100', 500, 320, 5, 'in_progress', NOW() + INTERVAL '6 hour'),
        ('WO-002', 2, 'P-220', 300, 300, 2, 'completed', NOW() - INTERVAL '2 hour'),
        ('WO-003', 1, 'P-105', 450, 0, 0, 'planned', NOW() + INTERVAL '18 hour'),
        ('WO-004', 3, 'P-330', 200, 0, 0, 'on_hold', NOW() + INTERVAL '1 day')
) AS data(order_code, line_id, product_code, planned_quantity, completed_quantity, scrap_quantity, status, due_time)
ON CONFLICT (order_code) DO NOTHING;

INSERT INTO line_shift_metrics (line_id, shift_id, planned_output, actual_output, downtime_minutes)
SELECT * FROM (
    VALUES
        (1, 1, 500, 320, 45),
        (2, 1, 280, 260, 60),
        (1, 2, 480, 0, 120)
) AS data(line_id, shift_id, planned_output, actual_output, downtime_minutes)
ON CONFLICT (line_id, shift_id) DO NOTHING;
