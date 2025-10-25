# Alpe Fresh Promotoras — Implementation Blueprint

## 1. Autenticación y Roles
- Implementar componentes Livewire personalizados para `login` y `logout`, centralizando validaciones y estados.
- Extender la migración `users` con campos `full_name`, `username`, `role`, `is_active`, `phone`, `avatar_path`.
- Definir enum `App\Enums\UserRole` (`admin`, `supervisor`, `analista`, `promotor`) y cast en el modelo.
- Seeder inicial (`database/seeders/AdminUserSeeder`) que cree a **Carlos Reyes (creyes/carina2230)** con rol `admin`.
- Middleware `EnsureUserIsActive` para bloquear cuentas inactivas, y policy base para resources configurables.
- Configurar redirect post-login hacia `/dashboard`; proteger rutas internas con `auth` y `verified` (si aplica).

## 2. Layout Global y Experiencia UI
- Layout maestro `resources/views/layouts/app.blade.php` con estructura `sidebar + header + content`.
- Sidebar fijo (colapsable) con gradiente morado → teal y menu basado en configuración (`config/navigation.php`).
- Header con toggle de sidebar, título dinámico, panel de usuario y switch modo oscuro (persistencia en local storage + Livewire store por usuario).
- Tema Tailwind: crear `tailwind.config.js` personalizado con colores de marca y plugin para modo oscuro (`class`).
- Componentes Blade reutilizables (`resources/views/components`) para tarjetas, botones, gráficos placeholders.
- Layout de autenticación `layouts/auth.blade.php` con gradiente y card central translúcida.

## 3. Modelo de Datos y Migraciones
- Crear migraciones y modelos para:
  - `chains`, `zones`, `stores` (con campos de localización geográfica y relación `chain_id`, `zone_id`).
  - `products` (precios mínimos/máximos).
  - Pivot `assignments` (`user_id`, `store_id`, `is_active`, `assigned_at`).
  - `evaluations` (flujo de visita; referencia a `assignment`, `store`, `product`, métricas de pasos).
  - `evaluation_photos` (morph a evaluaciones con tipo de paso).
  - `notification_settings` (preferencias por usuario/rol).
- Utilizar enums o `enum` columns para campos estructurados (`availability_status`, `incident_type`, etc.).
- Añadir índices útiles (unique para `username`, `assignments` activos, `stores` geolocalización).

## 4. Componentes Livewire Clave
- **Dashboard**: `Dashboard\Home`, `Dashboard\RecentActivity`.
- **Evaluaciones**: `Evaluations\Create` (flujo 6 pasos con wizard), `Evaluations\Index`.
- **Reportes**: `Reports\Dashboard` (filtros y gráficas).
- **Configuración**: CRUDs para `Users`, `Chains`, `Zones`, `Stores`, `Assignments`, `Products`, `EvaluationFields`, `Permissions`, `Notifications`.
- Componentes auxiliares: selectores dinámicos, modales, uploader de imágenes, tabla reutilizable con filtros/exportaciones.
- Uso de `Livewire\Attributes\Layout` y `Title` para SEO interno y breadcrumbs.

## 5. Flujo de Evaluación (6 pasos)
- Paso 1: selector de tienda con validación de geovalla (verificación backend usando coordenadas y distancia haversine).
- Paso 2-5: formularios step-by-step almacenando data en sesión/estado Livewire. Subir fotos a `storage/app/public/evaluations`.
- Paso 6: resumen editable previa persistencia. Al guardar, disparar eventos para notificaciones.
- Implementar `EvaluationService` para encapsular lógica multistep y `EvaluationPolicy`.

## 6. Reportes y Exportaciones
- Repositorios/query builders dedicados (`App\QueryBuilders\Report\*`) para métricas y filtros.
- Integración con `maatwebsite/excel` (revisar disponibilidad; de no existir, crear exportadores CSV propios) y generación PDF (dompdf/snappy).
- Construir gráficas en frontend con `Chart.js` (via `npm`) o alternativa sincrona; Livewire emitirá datasets.

## 7. Notificaciones y Auditoría
- Eventos `EvaluationSubmitted`, `IncidentReported`, etc. con listeners que consultan `notification_settings`.
- Persistir logs de actividad en tabla `activity_log` (evaluar paquete `spatie/laravel-activitylog`; si sin red, crear implementación simple).
- Configurar colas para procesos pesados (exports, notificaciones); fallback síncrono si cola inactiva.

## 8. Entregables Iterativos
1. **Sprint 1**: Autenticación, layout base, dashboard skeleton, modelo `users`.
2. **Sprint 2**: Migrations/models de catálogo (chains, zones, stores, products, assignments).
3. **Sprint 3**: Flujo Evaluaciones + Mis Visitas (listado y detalle).
4. **Sprint 4**: Reportes analíticos y exportaciones.
5. **Sprint 5**: Configuración avanzada, permisos, notificaciones, pulido UI/UX, modo oscuro persistente.

> Este blueprint guiará el backlog y priorización; ajustes se documentarán mediante notas de arquitectura adicionales en la carpeta `docs/`.
