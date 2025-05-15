<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $common_name
 * @property string $scientific_name
 * @property string $description
 * @property string|null $safety_notes
 * @property string|null $harvesting_tips
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlantDiscovery> $discoveries
 * @property-read int|null $discoveries_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Database\Factories\PlantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereCommonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereHarvestingTips($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereSafetyNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereScientificName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereUpdatedAt($value)
 */
	class Plant extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $plant_id
 * @property float|null $ai_confidence_score
 * @property string|null $admin_notes
 * @property string $latitude
 * @property string $longitude
 * @property string|null $area_name
 * @property int $is_protected_area
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PlantDiscoveryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereAiConfidenceScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereAreaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereIsProtectedArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery wherePlantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantDiscovery whereUserId($value)
 */
	class PlantDiscovery extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $plant_id
 * @property int $tag_id
 * @property float|null $relevance_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plant $plant
 * @property-read \App\Models\Tag $tag
 * @method static \Database\Factories\PlantTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag wherePlantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag whereRelevanceScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantTag whereUpdatedAt($value)
 */
	class PlantTag extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Plant> $plantTags
 * @property-read int|null $plant_tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPreference> $userPreferences
 * @property-read int|null $user_preferences_count
 * @method static \Database\Factories\TagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $notification_range_km
 * @property string $latitude
 * @property string $longitude
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlantDiscovery> $discoveries
 * @property-read int|null $discoveries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNotificationRangeKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $user_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tag $tag
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserPreferenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPreference whereUserId($value)
 */
	class UserPreference extends \Eloquent {}
}

