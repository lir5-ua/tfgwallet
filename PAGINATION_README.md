# Funcionalidad de Paginación Mejorada

## Descripción
Se ha implementado una funcionalidad para mostrar información detallada sobre los elementos mostrados y el total en los paginadores de la aplicación.

## Características
- **Información clara**: Muestra "Mostrando X a Y de Z elementos"
- **Componente reutilizable**: Usa un componente Blade para consistencia
- **Responsive**: Diseño centrado y adaptable
- **Personalizable**: Permite especificar el nombre de los elementos

## Componente Utilizado
```blade
<x-pagination-info :paginator="$variable" itemName="nombre_elementos" />
```

## Parámetros
- `paginator`: La variable de paginación de Laravel
- `itemName`: El nombre de los elementos (opcional, por defecto "elementos")

## Ejemplos de Uso

### En mascotas/index.blade.php
```blade
@if ($mascotas->hasPages())
<div style="margin-top: 20px; display: flex; justify-content: center; flex-direction: column; align-items: center;">
    <x-pagination-info :paginator="$mascotas" itemName="mascotas" />
    {{ $mascotas->links() }}
</div>
@endif
```

### En usuarios/index.blade.php
```blade
<div style="margin-top: 20px; display: flex; justify-content: center; flex-direction: column; align-items: center;">
    <x-pagination-info :paginator="$usuarios" itemName="usuarios" />
    {{ $usuarios->links() }}
</div>
```

## Resultado Visual
La información se muestra como:
```
Mostrando 1 a 10 de 25 mascotas
[Paginador de Laravel]
```

## Archivos Modificados
- `resources/views/components/pagination-info.blade.php` (nuevo)
- `resources/views/mascotas/index.blade.php`
- `resources/views/usuarios/index.blade.php`

## Beneficios
1. **Mejor UX**: Los usuarios saben exactamente cuántos elementos están viendo
2. **Consistencia**: Mismo formato en toda la aplicación
3. **Mantenibilidad**: Componente reutilizable y fácil de modificar
4. **Escalabilidad**: Fácil de implementar en nuevas vistas 