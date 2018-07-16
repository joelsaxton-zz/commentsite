<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Comment
 * @package App\Models
 *
 * Fillable Properties
 * @property int $id
 * @property int $root_id
 * @property int $parent_id
 * @property int $nesting_level
 * @property int $user_id
 * @property string $comment_text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Comment extends Model
{
    use SoftDeletes;

    const PROPERTY_ID = 'id';
    const PROPERTY_ROOT_ID = 'root_id';
    const PROPERTY_PARENT_ID = 'parent_id';
    const PROPERTY_NESTING_LEVEL = 'nesting_level';
    const PROPERTY_USER_ID = 'user_id';
    const PROPERTY_COMMENT_TEXT = 'comment_text';
    const PROPERTY_CREATED_AT = 'created_at';
    const TABLE = 'comments';
    const PRIMARY_KEY = self::PROPERTY_ID;

    protected $table = self::TABLE;

    protected $fillable = [
        self::PRIMARY_KEY,
        self::PROPERTY_USER_ID,
        self::PROPERTY_COMMENT_TEXT,
        self::PROPERTY_ROOT_ID,
        self::PROPERTY_PARENT_ID,
        self::PROPERTY_NESTING_LEVEL
    ];

    /**
     * Get root comment and all replies for this comment
     *
     * @return Collection
     */
    public function getAllComments()
    {
        if ($this->isRootComment()) {
            return Comment::where(self::PROPERTY_ROOT_ID, $this->getKey())
                ->with(['user'])
                ->orderBy(Comment::PROPERTY_CREATED_AT)
                ->get();
        }

        return collect([]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, self::PROPERTY_USER_ID);
    }

    /**
     * @return bool
     */
    public function isRootComment()
    {
        return is_null($this->{self::PROPERTY_PARENT_ID});
    }

}
