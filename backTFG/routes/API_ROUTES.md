# API Routes — TFG Time Tracker

Base URL: `/api/v1`

Todas las respuestas usan JSON. Los errores devuelven `{ "msg": "...", "error": "..." }`.  
Los registros con `active` nunca se borran físicamente — `DELETE` los desactiva (`active: false`).  
Por defecto todos los listados muestran solo registros activos. Usa `?active=false` para ver inactivos.

---

## Empresas — `CompanyController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/companies` | Lista empresas. Filtros: `search` (nombre/CIF), `active`. Paginado. |
| GET | `/companies/{id}` | Detalle con departamentos (+ manager) y festivos asignados. |
| POST | `/companies` | Crea empresa. Campos: `name`, `tax_id`, `address?`. El observer adjunta todos los festivos automáticamente. |
| PUT/PATCH | `/companies/{id}` | Actualiza `name`, `tax_id`, `address`, `active`. |
| DELETE | `/companies/{id}` | Desactiva la empresa (`active: false`). |
| POST | `/companies/{id}/holidays/attach` | Asocia un festivo. Body: `holiday_id`. |
| POST | `/companies/{id}/holidays/detach` | Desvincula un festivo. Body: `holiday_id`. |

---

## Departamentos — `DepartmentController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/departments` | Lista departamentos con empresa. Filtros: `company_id`, `search`, `active`. Paginado. |
| GET | `/departments/{id}` | Detalle con empresa, manager y lista de empleados (con rol). |
| POST | `/departments` | Crea departamento. Campos: `company_id`, `name`, `manager_id?`. |
| PUT/PATCH | `/departments/{id}` | Actualiza `company_id`, `name`, `manager_id`, `active`. |
| DELETE | `/departments/{id}` | Desactiva el departamento. |

---

## Usuarios — `UserController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/users` | Lista usuarios con departamento y rol. Filtros: `department_id`, `role_id`, `search`, `active`. Paginado. |
| GET | `/users/{id}` | Detalle con departamento (+ empresa) y rol. |
| POST | `/users` | Crea usuario. Campos: `name`, `last_name`, `email`, `password`, `hire_date`, `role_id`, `department_id?`. La contraseña se hashea. |
| PUT/PATCH | `/users/{id}` | Actualiza cualquier campo, incluido `active`. La contraseña se hashea si se envía. |
| DELETE | `/users/{id}` | Desactiva el usuario. |

---

## Roles — `RoleController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/roles` | Lista roles con sus permisos. Filtros: `search`, `active`. |
| GET | `/roles/{id}` | Detalle con permisos. |
| POST | `/roles` | Crea rol. Campos: `name`, `permission_ids[]?`. |
| PUT/PATCH | `/roles/{id}` | Actualiza `name`, `active`. Si se envía `permission_ids[]`, reemplaza los permisos (`sync`). |
| DELETE | `/roles/{id}` | Desactiva el rol. |

---

## Permisos — `PermissionController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/permissions` | Lista todos los permisos. Filtro: `search`. |
| GET | `/permissions/{id}` | Detalle con los roles que lo tienen asignado. |
| POST | `/permissions` | Crea permiso. Campo: `name`. |
| PUT/PATCH | `/permissions/{id}` | Actualiza `name`. |
| DELETE | `/permissions/{id}` | Elimina el permiso (borrado físico, se desvincula de roles antes). |

---

## Fichajes — `TimeLogController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/time-logs` | Lista fichajes con usuario. Filtros: `user_id`, `date_from`, `date_to`, `department_id`, `active`. Paginado. |
| GET | `/time-logs/{id}` | Detalle con usuario (+ departamento) e incidencias (+ tipo). |
| POST | `/time-logs` | Registra fichaje. Campos: `user_id`, `date`, `check_in?`, `check_out?`. |
| PUT/PATCH | `/time-logs/{id}` | Corrige `date`, `check_in`, `check_out`, `active`. |
| DELETE | `/time-logs/{id}` | Desactiva el fichaje. |

---

## Incidencias de fichaje — `TimeLogIssueController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/time-log-issues` | Lista incidencias con tipo y usuario notificador. Filtros: `time_log_id`, `user_id`, `issue_type_id`, `resolved`. Paginado. |
| GET | `/time-log-issues/{id}` | Detalle con fichaje (+ usuario), tipo de incidencia y notificador. |
| POST | `/time-log-issues` | Crea incidencia. Campos: `time_log_id`, `user_id`, `issue_type_id`, `description?`, `resolved?`. |
| PUT/PATCH | `/time-log-issues/{id}` | Actualiza `issue_type_id`, `description`, `resolved`. |
| DELETE | `/time-log-issues/{id}` | Elimina la incidencia (borrado físico). |

---

## Tipos de incidencia — `IssueTypeController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/issue-types` | Lista tipos. Filtros: `search`, `active`. |
| GET | `/issue-types/{id}` | Detalle de un tipo. |
| POST | `/issue-types` | Crea tipo. Campo: `name`. |
| PUT/PATCH | `/issue-types/{id}` | Actualiza `name`, `active`. |
| DELETE | `/issue-types/{id}` | Desactiva el tipo. |

---

## Tipos de ausencia — `AbsenceTypeController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/absence-types` | Lista tipos. Filtros: `search`, `active`. |
| GET | `/absence-types/{id}` | Detalle. |
| POST | `/absence-types` | Crea tipo. Campo: `name`. |
| PUT/PATCH | `/absence-types/{id}` | Actualiza `name`, `active`. |
| DELETE | `/absence-types/{id}` | Desactiva el tipo. |

---

## Solicitudes de ausencia — `AbsenceRequestController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/absence-requests` | Lista solicitudes con usuario y tipo. Filtros: `user_id`, `absence_type_id`, `status`, `date_from`, `date_to`, `department_id`, `active`. Paginado. |
| GET | `/absence-requests/{id}` | Detalle con usuario (+ departamento), tipo y aprobación (+ aprobador). |
| POST | `/absence-requests` | Crea solicitud. Campos: `user_id`, `absence_type_id`, `start_date`, `end_date`, `comments?`. Estado inicial: `pending`. |
| PUT/PATCH | `/absence-requests/{id}` | Actualiza `absence_type_id`, `start_date`, `end_date`, `status` (`pending/approved/rejected`), `comments`, `active`. |
| DELETE | `/absence-requests/{id}` | Desactiva la solicitud. |

---

## Aprobaciones — `ApprovalController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/approvals` | Lista aprobaciones con solicitud y aprobador. Filtros: `approved_by`, `absence_request_id`. Paginado. |
| GET | `/approvals/{id}` | Detalle con solicitud (+ usuario) y aprobador. |
| POST | `/approvals` | Registra aprobación. Campos: `absence_request_id`, `approved_by`, `status` (`approved/rejected`), `comments?`. |
| PUT/PATCH | `/approvals/{id}` | Actualiza `status`, `comments`. |
| DELETE | `/approvals/{id}` | Elimina la aprobación (borrado físico). |

---

## Días de la semana — `DayController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/days` | Lista días. Filtro: `active`. |
| GET | `/days/{id}` | Detalle. |
| POST | `/days` | Crea día. Campo: `name`. |
| PUT/PATCH | `/days/{id}` | Actualiza `name`, `active`. |
| DELETE | `/days/{id}` | Desactiva el día. |

---

## Turnos — `ShiftController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/shifts` | Lista turnos. Filtros: `search`, `active`. |
| GET | `/shifts/{id}` | Detalle con horario. |
| POST | `/shifts` | Crea turno. Campos: `name`, `start_time` (HH:MM:SS), `end_time` (HH:MM:SS). |
| PUT/PATCH | `/shifts/{id}` | Actualiza `name`, `start_time`, `end_time`, `active`. |
| DELETE | `/shifts/{id}` | Desactiva el turno. |

---

## Turnos asignados a usuarios — `UserShiftController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/user-shifts` | Lista asignaciones con usuario, turno y día. Filtros: `user_id`, `shift_id`, `day_id`, `department_id`. Paginado. |
| GET | `/user-shifts/{id}` | Detalle de una asignación. |
| POST | `/user-shifts` | Asigna turno a usuario. Campos: `user_id`, `shift_id`, `day_id`. |
| PUT/PATCH | `/user-shifts/{id}` | Cambia `shift_id` o `day_id`. |
| DELETE | `/user-shifts/{id}` | Elimina la asignación (borrado físico). |

---

## Festivos — `HolidayController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/holidays` | Lista festivos ordenados por fecha. Filtros: `search`, `year`, `company_id`. |
| GET | `/holidays/{id}` | Detalle con empresas que tienen el festivo asignado. |
| POST | `/holidays` | Crea festivo. Campos: `date` (único), `name`. |
| PUT/PATCH | `/holidays/{id}` | Actualiza `date`, `name`. |
| DELETE | `/holidays/{id}` | Elimina el festivo y lo desvincula de todas las empresas (borrado físico). |

---

## Notificaciones — `NotificationController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/notifications` | Lista notificaciones. Filtros: `user_id`, `is_read`. Paginado. |
| GET | `/notifications/{id}` | Detalle. |
| POST | `/notifications` | Crea notificación. Campos: `user_id`, `message`. |
| PATCH | `/notifications/{id}/read` | Marca una notificación como leída. |
| POST | `/notifications/read-all` | Marca todas las no leídas de un usuario como leídas. Body: `user_id`. |
| DELETE | `/notifications/{id}` | Elimina la notificación (borrado físico). |

---

## Adjuntos — `AttachmentController`

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/attachments` | Lista adjuntos. Filtros: `entity_type`, `entity_id`. Paginado. |
| GET | `/attachments/{id}` | Detalle. |
| POST | `/attachments` | Registra adjunto. Campos: `entity_type`, `entity_id`, `file_url`. |
| PUT/PATCH | `/attachments/{id}` | Actualiza `file_url`. |
| DELETE | `/attachments/{id}` | Elimina el adjunto (borrado físico). |

---

## Auditoría — `AuditLogController` *(solo lectura)*

| Método | Ruta | Descripción |
|--------|------|-------------|
| GET | `/audit-logs` | Lista logs con usuario. Filtros: `user_id`, `action`, `date_from`, `date_to`. Paginado. |
| GET | `/audit-logs/{id}` | Detalle con usuario que realizó la acción. |
