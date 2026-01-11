# Notifications & Chat Support - Quick Reference

## 🚀 Quick Start

### Access the Features
1. Login as Admin: http://localhost:8000/login
2. Navigate to Admin Dashboard
3. Click on "Communication" section in sidebar
4. Choose "Notifications" or "Chat Support"

### Run Sample Data
```bash
php artisan db:seed --class=NotificationChatSeeder
```

## 📌 API Endpoints

### Notifications
```bash
# Get all notifications
GET /notifications?type=all&status=all

# Get unread count
GET /notifications/unread-count

# Create notification
POST /notifications
{
    "user_id": 1,
    "type": "success",
    "title": "Hello",
    "message": "Welcome!"
}

# Mark as read
POST /notifications/{id}/read

# Mark all as read
POST /notifications/mark-all-read

# Delete
DELETE /notifications/{id}
```

### Chat
```bash
# Get conversations
GET /chat/conversations

# Create conversation
POST /chat/conversations
{
    "subject": "Help needed",
    "message": "I need assistance",
    "priority": "normal"
}

# Get messages
GET /chat/conversations/{id}/messages

# Send message
POST /chat/conversations/{id}/messages
{
    "message": "Hello!"
}

# Update status
PATCH /chat/conversations/{id}/status
{
    "status": "resolved"
}
```

## 🎨 UI Sections

### Notifications Section
- **Location:** Admin Dashboard → Communication → Notifications
- **Features:** Create, filter, mark read, delete
- **Stats:** Total, Unread, Today

### Chat Section
- **Location:** Admin Dashboard → Communication → Chat Support
- **Features:** Conversations list, messaging, status updates
- **Stats:** Active, Pending, Resolved Today, Avg Response Time

### Header Bell
- **Location:** Top right corner (all pages)
- **Features:** Unread count, dropdown preview
- **Auto-refresh:** Every 30 seconds

## 💾 Database Tables

```sql
-- Notifications
notifications (
    id, user_id, type, title, message, 
    data, read_at, created_at, updated_at
)

-- Chat Conversations
chat_conversations (
    id, user_id, admin_id, subject, status, 
    priority, last_message_at, created_at, updated_at
)

-- Chat Messages
chat_messages (
    id, conversation_id, user_id, message, 
    attachment_path, is_admin, read_at, 
    created_at, updated_at
)
```

## 🔧 Configuration

### .env Settings (if needed for future WebSocket)
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

## 📝 Code Examples

### Create Notification in Controller
```php
use App\Models\Notification;

Notification::create([
    'user_id' => $user->id,
    'type' => 'success',
    'title' => 'Order Confirmed',
    'message' => 'Your order has been confirmed.',
    'data' => ['order_id' => 123]
]);
```

### Get Unread Notifications
```php
$unreadCount = Auth::user()
    ->notifications()
    ->unread()
    ->count();
```

### Start Chat Conversation
```php
use App\Models\ChatConversation;
use App\Models\ChatMessage;

$conversation = ChatConversation::create([
    'user_id' => $user->id,
    'subject' => 'Need help',
    'status' => 'open',
    'priority' => 'normal'
]);

ChatMessage::create([
    'conversation_id' => $conversation->id,
    'user_id' => $user->id,
    'message' => 'Hello, I need help!',
    'is_admin' => false
]);
```

### JavaScript - Load Notifications
```javascript
async function loadNotifications() {
    const response = await fetch('/notifications');
    const data = await response.json();
    console.log(data.data); // Array of notifications
}
```

### JavaScript - Send Chat Message
```javascript
async function sendMessage(conversationId, message) {
    const response = await fetch(`/chat/conversations/${conversationId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message })
    });
    return await response.json();
}
```

## 🎯 Notification Types

| Type | Color | Icon | Use Case |
|------|-------|------|----------|
| info | Blue (#3b82f6) | fa-info-circle | General information |
| success | Green (#10b981) | fa-check-circle | Success messages |
| warning | Orange (#f59e0b) | fa-exclamation-triangle | Warnings |
| error | Red (#ef4444) | fa-times-circle | Error messages |

## 🔄 Chat Status Values

| Status | Badge Color | Description |
|--------|-------------|-------------|
| open | Blue | Newly created, awaiting response |
| in_progress | Orange | Admin is handling |
| resolved | Green | Issue resolved |
| closed | Gray | Conversation closed |

## 🛡️ Security Checklist

- ✅ CSRF token required for all mutations
- ✅ User authentication required
- ✅ Authorization checks (users see only their data)
- ✅ Input validation on all endpoints
- ✅ XSS protection via blade escaping
- ✅ SQL injection prevention via Eloquent ORM

## 🐛 Debugging

### Check Notifications
```bash
php artisan tinker
>>> App\Models\Notification::count()
>>> App\Models\Notification::latest()->first()
```

### Check Chat
```bash
php artisan tinker
>>> App\Models\ChatConversation::count()
>>> App\Models\ChatConversation::with('messages')->first()
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 📱 Mobile Responsiveness

Both features are fully responsive:
- Collapsible sidebar on mobile
- Adaptive grid layouts
- Touch-friendly buttons
- Optimized for tablets and phones

## ⚡ Performance Tips

1. **Pagination:** Use `paginate(20)` for large datasets
2. **Eager Loading:** Load relationships with `with()`
3. **Indexing:** Already added on key columns
4. **Caching:** Consider caching unread counts for high traffic

## 🎉 Success Indicators

**Notifications Working:**
- ✅ Badge shows in header
- ✅ Dropdown shows latest notifications
- ✅ Can create/read/delete notifications
- ✅ Stats update correctly

**Chat Working:**
- ✅ Can create conversations
- ✅ Messages send and receive
- ✅ Status updates work
- ✅ Conversation list updates

## 📞 Support

If you encounter issues:
1. Check browser console for errors
2. Review Laravel logs: `storage/logs/laravel.log`
3. Verify database connections
4. Ensure migrations ran successfully
5. Check CSRF token is present

## 🚀 Next Steps

Consider adding:
- Email notifications
- WebSocket real-time updates
- Mobile app integration
- Analytics dashboard
- Automated responses
- File attachments

---

**Version:** 1.0  
**Last Updated:** January 11, 2026  
**Author:** Alpha Vendor Development Team
