<?php

use App\Models\User;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PlantController;
use App\Http\Controllers\API\PlantTagController;
use App\Http\Controllers\API\auth\AuthController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PlantDiscoveryController;
use App\Http\Controllers\API\UserPreferenceController;
use App\Http\Controllers\API\SeasonAvailabilityController;

Route::prefix('auth')->group(
    function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    }
);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    /**
     * @group User Management
     */
    Route::prefix('users')->group(function () {
        Route::get('/me', [UserController::class, 'profile'])->name('users.profile');
        Route::put('/me', [UserController::class, 'update'])->name('users.update');

        /**
         * @group User Preferences
         */
        Route::prefix('me/preferences')->group(function () {
            Route::get('/', [UserPreferenceController::class, 'index'])->name('user.preferences.index');
            Route::post('/', [UserPreferenceController::class, 'store'])->name('user.preferences.store');
            Route::delete('/{tag}', [UserPreferenceController::class, 'destroy'])->name('user.preferences.destroy');
        });
    });

    /**
     * @group Tags
     */
    Route::prefix('tags')->group(function () {
        // Public routes (available to all authenticated users)
        Route::get('/', [TagController::class, 'index'])->name('tags.index');

        // Admin-only routes
        Route::middleware(['auth:sanctum', 'can:admin'])->group(function () {
            Route::post('/', [TagController::class, 'store'])->name('tags.store');
            Route::delete('/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
            Route::put('/{tag}', [TagController::class, 'update'])->name('tags.update');
        });
    });
    /**
     * @group Plants
     */
    Route::prefix('plants')->group(function () {
        Route::get('/', [PlantController::class, 'index'])->name('plants.index');
        Route::get('/{plant}', [PlantController::class, 'show'])->name('plants.show');

        // Plant Relationships
        Route::get('/{plant}/tags', [PlantTagController::class, 'index'])->name('plants.tags.index');
        Route::get('/{plant}/discoveries', [PlantTagController::class, 'discoveries'])->name('plants.tags.discoveries');
    });

    /**
     * @group Discoveries
     */
    Route::prefix('discoveries')->group(function () {
        Route::get('/', [PlantDiscoveryController::class, 'index'])->name('discoveries.index');
        Route::post('/', [PlantDiscoveryController::class, 'store'])->name('discoveries.store');
        Route::get('/{discovery}', [PlantDiscoveryController::class, 'show'])->name('discoveries.show');
        Route::put('/{discovery}', [PlantDiscoveryController::class, 'update'])->name('discoveries.update');
        Route::delete('/{discovery}', [PlantDiscoveryController::class, 'destroy'])->name('discoveries.destroy');
    });

    /**
     * @group Notifications
     */
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    });
});
