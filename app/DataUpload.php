<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class DataUpload extends Model
{
    protected $fillable = [
        'title',
        'site',
        'note',
        'type',
        'extension',
        'user_id'
    ];

    public static $image_ext = ['jpg', 'jpeg', 'png', 'gif'];
    public static $audio_ext = ['mp3', 'ogg', 'mpga'];
    public static $video_ext = ['mp4', 'mpeg'];
    public static $document_ext = ['doc', 'docx', 'pdf', 'odt','txt','zip'];

    /**
     * Get maximum file size
     * @return int maximum file size in kilobites
     */
    public static function getMaxSize()
    {
        return (int)ini_get('upload_max_filesize') * 1000;
    }

    /**
     * Get directory for the specific user
     * @return string Specific user directory
     */
    public function getUserDir()
    {
        return Auth::id();
    }

    /**
     * Get all extensions
     * @return array Extensions of all file types
     */
    public static function getAllExtensions()
    {
        $merged_arr = array_merge(self::$image_ext, self::$audio_ext, self::$video_ext, self::$document_ext);
        return implode(',', $merged_arr);
    }

    /**
     * Get type by extension
     * @param  string $ext Specific extension
     * @return string      Type
     */
    public function getType($ext)
    {
        if (in_array($ext, self::$image_ext)) {
            return 'image';
        }

        if (in_array($ext, self::$audio_ext)) {
            return 'audio';
        }

        if (in_array($ext, self::$video_ext)) {
            return 'video';
        }

        if (in_array($ext, self::$document_ext)) {
            return 'document';
        }
    }

    /**
     * Get file name and path to the file
     * @param  string $type      Data type
     * @param  string $name      File name
     * @param  string $extension File extension
     * @return string            File name with the path
     */
    public function getName($type, $name, $extension)
    {
        return 'public/' . $this->getUserDir() . '/' . $type . '/' . $name . '.' . $extension;
    }

    /**
     * Upload file to the server
     * @param  string $type      File type
     * @param  object $file      Uploaded file from request
     * @param  string $name      File name
     * @param  string $extension File extension
     * @return string|boolean    String if file successfully uploaded, otherwise - false
     */
    public function upload($type, $file, $name, $extension)
    {
        $path = 'public/' . $this->getUserDir() . '/' . $type;
        $full_name = $name . '.' . $extension;

        return Storage::putFileAs($path, $file, $full_name);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
