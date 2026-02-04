# GitHub Copilot Instructions for Lavacar

## Project Overview
Lavacar is a Laravel 12 application for managing car wash service bookings. It's a multi-tenant system where companies (car wash establishments) offer services to customers through a booking system.

## Architecture & Key Concepts

### Multi-Tenant Structure
- **Companies**: Car wash establishments with CNPJ, address, ratings, and operational settings
- **Users**: Can be ADMIN (system-wide) or PARTNER (company-specific) with selected_company_id
- **Company Roles**: OWNER, MANAGER, EMPLOYEE (stored in company_user pivot table)
- **Data Scoping**: PARTNER users see only data from their selected_company_id via CompanyScope

### Core Models & Relationships
- `Company` → `hasMany` Bookings, Customers, CompanyVehicles, OpeningHours, ServiceTypes, ServiceVariants, Users
- `Booking` → belongsTo Company, Customer, CompanyVehicle, ServiceVariant
- `Customer` → belongsTo Company, hasMany Bookings, CompanyVehicles
- `User` → belongsToMany Companies (with roles), has selected_company_id/selected_company_role

### Key Patterns

#### Global Scopes
Apply `CompanyScope` to models that should be scoped to the current user's company:
```php
protected static function booted()
{
    parent::booted();
    static::addGlobalScope(new CompanyScope);
}
```

#### Livewire Components
- Use anonymous classes in `resources/views/pages/{namespace}/{component}.php`
- Components are registered via `Route::livewire()` with `pages::` namespace
- Example: `Route::livewire('/bookings/create', 'pages::bookings.create')`

#### TallStack UI Integration
- Use TS components: `<x-ts-card>`, `<x-ts-select>`, `<x-ts-button>`, etc.
- Dynamic selects via API routes (e.g., `/api/customers?company_id=X`)
- Personalizations in `AppServiceProvider::boot()`

#### Enums
- `BookingStatusEnum`, `CompanyRoleEnum`, `VehicleSizeEnum`, `WeekdayEnum`
- Used for casts in models

#### API Endpoints for Selects
Return formatted arrays for TallStack selects:
```php
return Model::query()
    ->where('company_id', $company_id)
    ->get()
    ->map(fn($item) => [
        'label' => $item->name,
        'description' => $item->detail,
        'value' => $item->id,
    ]);
```

## Development Workflow

### Environment Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run dev  # or npm run build
```

### Testing
- Use Pest with `RefreshDatabase` trait
- Run tests: `./vendor/bin/pest`
- Test files in `tests/Feature/` and `tests/Unit/`

### Building Assets
- `npm run dev` for development
- `npm run build` for production
- Uses Vite with Laravel plugin

### Key Commands
- `php artisan migrate` - Run migrations
- `php artisan db:seed` - Seed database
- `php artisan tinker` - REPL
- `php artisan debugbar:clear` - Clear debug bar data

## Code Style & Conventions

### Brazilian Localization
- Use `pt-BR` locale for dates/currency (e.g., `locale="pt-BR"` in TS components)
- CNPJ validation and formatting
- WhatsApp integration for companies/customers

### Naming Conventions
- Models: PascalCase (Company, Booking)
- Tables: snake_case plural (companies, bookings)
- Foreign keys: `{model}_id` (company_id, customer_id)

### Security & Policies
- `AdminPolicy` for role checks (`isAdmin()`, `isOwner()`)
- Middleware: `auth`, `admin`
- Soft deletes on critical models

### UI/UX Patterns
- Page headers with `<div class="page-header"><h2>Title</h2></div>`
- Card-based layouts with `<x-ts-card>`
- Toast notifications via `$this->toast()->success()->send()`

## Common Implementation Patterns

### Creating Bookings
1. Select customer (via API with search)
2. Select customer's vehicle
3. Select service variant (filtered by vehicle size)
4. Auto-populate price from service variant
5. Select date/time (constrained by opening hours)
6. Calculate end time from service duration

### Dynamic Form Updates
Use Livewire listeners and computed properties:
```php
#[On('customerSelected')]
public function updateVehicles() { /* ... */ }
```

### Model Factories
Use Brazilian faker providers:
```php
'name' => fake()->company(),
'cnpj' => fake()->cnpj(),
```

## File Structure Reference
- `app/Models/` - Eloquent models with relationships and scopes
- `app/Enums/` - Status and role enums
- `resources/views/pages/` - Livewire view components
- `database/migrations/` - Schema definitions
- `database/factories/` - Model factories for testing
- `routes/web.php` - Livewire routes with `pages::` namespace
- `routes/api.php` - JSON endpoints for dynamic selects