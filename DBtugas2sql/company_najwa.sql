create or replace function get_employees_by_salary_range(min_salary numeric, max_salary numeric)
returns table (
  id int,
  full_name text,
  department varchar,
  "position" varchar,
  salary numeric)
language sql
as $$
  select
    id,
    concat(first_name, ' ', last_name) as full_name,
    department,
    position,
    salary
  from employees
  where salary between min_salary and max_salary
  order by salary, id
$$;

-- function 2: ringkasan statistik per departemen
create or replace function get_department_summary()
returns table (
  department varchar,
  employee_count bigint,
  avg_salary numeric,
  total_budget numeric
)
language sql
as $$
  with depts as (
    select department from employees
    union
    select department from projects
  )
  select
    d.department,
    count(e.id) as employee_count,
    round(avg(e.salary), 2) as avg_salary,
    coalesce(sum(p.budget), 0) as total_budget
  from depts d
  left join employees e on e.department = d.department
  left join projects  p on p.department  = d.department
  group by d.department
  order by d.department
$$;