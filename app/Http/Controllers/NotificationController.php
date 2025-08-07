<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
   public function index()
    {
        $user = Auth::user();

        if (!$user) {
            // Ici, tu peux rediriger vers la page de login, ou afficher une erreur
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir les notifications.');
        }

        $notifications = $user->notifications; // ou unreadNotifications si tu veux que celles non lues

        return view('emails.notification', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Rediriger vers le lien de la notif ou une autre page
        $redirectUrl = $notification->data['link'] ?? route('notifications.index');
        return redirect($redirectUrl);
    }


}
