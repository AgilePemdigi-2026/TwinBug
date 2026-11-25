<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Default notification preference flags.
     *
     * @return array<string, bool>
     */
    public static function defaultNotifications(): array
    {
        return [
            'submission_confirmation' => true,
            'detection_completed' => true,
            'duplicate_results' => true,
            'status_updates' => false,
            'developer_reviews' => true,
        ];
    }

    /**
     * Display the Manage Settings page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $notifications = array_merge(
            self::defaultNotifications(),
            $user->notification_preferences ?? [],
        );

        return view('settings.index', [
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Persist account preferences, notifications, and appearance settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language' => ['required', 'string', Rule::in(['en', 'id'])],
            'timezone' => ['required', 'string', Rule::in(['Asia/Jakarta', 'UTC', 'Asia/Singapore', 'America/New_York'])],
            'date_format' => ['required', 'string', Rule::in(['DD MMM YYYY', 'YYYY-MM-DD', 'MM/DD/YYYY'])],
            'theme' => ['required', 'string', Rule::in(['light', 'dark', 'system'])],
            'active_tab' => ['nullable', 'string', Rule::in(['general', 'preferences', 'notifications', 'security', 'appearance'])],
            'notifications' => ['nullable', 'array'],
        ]);

        $incomingNotifications = $request->input('notifications', []);
        $notificationPreferences = [];
        foreach (array_keys(self::defaultNotifications()) as $key) {
            $notificationPreferences[$key] = filter_var($incomingNotifications[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        $request->user()->update([
            'language' => $validated['language'],
            'timezone' => $validated['timezone'],
            'date_format' => $validated['date_format'],
            'theme' => $validated['theme'],
            'notification_preferences' => $notificationPreferences,
        ]);

        $request->session()->put('locale', $validated['language']);

        return redirect()
            ->route('settings', ['tab' => $validated['active_tab'] ?? 'general'])
            ->with('status', 'settings-updated');
    }

    /**
     * Reset user preferences back to system defaults.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->user()->update([
            'language' => 'en',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'DD MMM YYYY',
            'theme' => 'light',
            'notification_preferences' => self::defaultNotifications(),
        ]);

        $request->session()->put('locale', 'en');

        return redirect()
            ->route('settings', ['tab' => $request->input('active_tab', 'general')])
            ->with('status', 'settings-reset');
    }
}
