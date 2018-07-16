<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController
 * @package App\Http\Controllers
 */
class CommentController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Gets all the root comments for initial display on the page
     *
     * @return mixed
     */
    public function getCommentRootNodes()
    {
        return CommentRepository::getCommentRootNodes();
    }

    /**
     * Get comment tree for root comment with id $id
     *
     * @param Request $request
     * @return string
     */
    public function getCommentTree(Request $request)
    {
        // @todo wrap in try-catch
        $comment = Comment::findOrFail($request->input('id'));

        return CommentRepository::getCommentTree($comment);
    }

    /**
     * Create a new comment thread based on this root comment
     *
     * @param Request $request
     * @return array
     */
    public function createRootComment(Request $request)
    {
        // @todo wrap in try-catch
        $user = Auth::user();
        return CommentRepository::createRootComment($user, $request->input('comment_text'));
    }

    /**
     * Create comment response to parent thread
     *
     * @param Request $request
     * @return array
     */
    public function createCommentResponse(Request $request)
    {
        // @todo wrap in try-catch
        $parent = Comment::findOrFail($request->input('parent_id'));
        $user = Auth::user();
        $commentText = $request->input('comment_text');

        return CommentRepository::createResponseToComment($parent, $user, $commentText);
    }
}
