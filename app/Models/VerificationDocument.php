<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the user that owns the document
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL for the document
     */
    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get human-readable document type
     */
    public function getDocumentTypeNameAttribute()
    {
        return match($this->document_type) {
            'nid_front' => 'NID Card (Front)',
            'nid_back' => 'NID Card (Back)',
            'trade_license' => 'Trade License',
            'personal_photo' => 'Personal Photo',
            'shop_profile' => 'Shop Profile Picture',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Scope to get documents by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope to get pending documents
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved documents
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected documents
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
