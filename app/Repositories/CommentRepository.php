<?php namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class CommentRepository {

    const ERROR_MAX_NESTING_LEVEL_REACHED = 'Max nesting level reached, cannot add comment.';
    const ERROR_COMMENT_TEXT_TOO_LONG = 'Comment text was too long.';
    const ERROR_SQL_CREATING_COMMENT = 'There was an error creating this comment.';
    const SUCCESS_COMMENT_CREATED = 'Comment created successfully!';

    /**
     * Get all root comment nodes for initial display
     */
    public static function getCommentRootNodes()
    {
        return Comment::whereNull(Comment::PROPERTY_PARENT_ID)->with(['user'])->get();
    }

    /**
     * Get comment tree for a root comment
     *
     * @param Comment $comment
     * @return string
     */
    public static function getCommentTree(Comment $comment)
    {
        $allComments = $comment->getAllComments();

        return self::organizeCommentTree($allComments);
    }

    /**
     * @param Authenticatable $user
     * @param $commentText
     * @return array
     */
    public static function createRootComment(Authenticatable $user, $commentText)
    {
        if (self::commentTooLong($commentText)) {
            return self::sendErrorResponse(self::ERROR_COMMENT_TEXT_TOO_LONG);
        }

        return self::createNewComment($user, $commentText);
    }

    /**
     * @param Comment $parent
     * @param Authenticatable $user
     * @param $commentText
     * @return array
     */
    public static function createResponseToComment(Comment $parent, Authenticatable $user, $commentText)
    {
        if (self::commentOverNestingLevelLimit($parent)) {
            return self::sendErrorResponse(self::ERROR_MAX_NESTING_LEVEL_REACHED);
        }

        if (self::commentTooLong($commentText)) {
            return self::sendErrorResponse(self::ERROR_COMMENT_TEXT_TOO_LONG);
        }

        return self::createResponseComment($parent, $user, $commentText);
    }

    /**
     *
     * @param Collection $comments
     * @return string
     */
    private static function organizeCommentTree(Collection $comments)
    {
        $commentsIndexed = collect([]);

        foreach ($comments as $comment) {
            $commentsIndexed->put($comment->id, $comment);
        }

        foreach ($comments as $key => $comment) {
            $commentsIndexed->get($comment->id)->replies = collect([]);

            if (!is_null($comment->parent_id)) {
                $commentsIndexed->get($comment->parent_id)->replies->push($comment);
                unset($comments[$key]);
            }
        }

        return json_encode($comments);
    }

    /**
     * @param Authenticatable $user
     * @param $commentText
     * @return array
     */
    private static function createNewComment(Authenticatable $user, $commentText)
    {
        $commentText = self::sanitizeComment($commentText);

        try {

            $new = Comment::create([
                Comment::PROPERTY_PARENT_ID => null,
                Comment::PROPERTY_COMMENT_TEXT => $commentText,
                Comment::PROPERTY_NESTING_LEVEL => 0,
                Comment::PROPERTY_USER_ID => $user->getKey()
            ]);

            $new->{Comment::PROPERTY_ROOT_ID} = $new->getKey();
            $new->save();

            return self::sendSuccessResponse();

        } catch (\Exception $e) {
            return self::sendErrorResponse(self::ERROR_SQL_CREATING_COMMENT);
        }
    }

    /**
     * @param Comment $parent
     * @param Authenticatable $user
     * @param $commentText
     * @return array
     */
    private static function createResponseComment(Comment $parent, Authenticatable $user, $commentText)
    {
        $commentText = self::sanitizeComment($commentText);

        try {

            Comment::create([
                Comment::PROPERTY_PARENT_ID => $parent->getKey(),
                Comment::PROPERTY_ROOT_ID => $parent->{Comment::PROPERTY_ROOT_ID},
                Comment::PROPERTY_COMMENT_TEXT => $commentText,
                Comment::PROPERTY_NESTING_LEVEL => $parent->{Comment::PROPERTY_NESTING_LEVEL} + 1,
                Comment::PROPERTY_USER_ID => $user->getKey()
            ]);

            return self::sendSuccessResponse();

        } catch (\Exception $e) {
            return self::sendErrorResponse(self::ERROR_SQL_CREATING_COMMENT);
        }
    }

    /**
     * Makes sure comment is not over nesting level limit.
     * This is first enforced by the front end JS but this is a final safety check
     *
     * @param Comment $parent
     * @return bool
     */
    private static function commentOverNestingLevelLimit(Comment $parent)
    {
        $nestingLevel = $parent->getNestingLevel() + 1;

        return $nestingLevel >= config('comment.max_nesting_level');
    }

    /**
     * Makes sure comment is not too long
     * This is first enforced by the front end JS but this is a final safety check
     *
     * @param string $commentText
     * @return bool
     */
    private static function commentTooLong(string $commentText)
    {
        return strlen($commentText) > config('comment.max_length');
    }

    /**
     * This will encode any html entities. There is no
     * raw SQL as everything must go through Eloquent.
     *
     * @param $comment
     * @return string
     */
    private static function sanitizeComment($comment)
    {
        return htmlspecialchars($comment);
    }

    /**
     * @return array
     */
    private static function sendSuccessResponse()
    {
        return ['success' => 1, 'message' => self::SUCCESS_COMMENT_CREATED];
    }

    /**
     * @param $message
     * @return array
     */
    private static function sendErrorResponse($message)
    {
        return ['success' => 0, 'message' => $message];
    }
}
