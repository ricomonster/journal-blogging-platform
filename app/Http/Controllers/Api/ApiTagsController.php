<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Tag\TagRepositoryInterface;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiTagsController extends ApiController
{
    protected $tags;

    public function __construct(TagRepositoryInterface $tags)
    {
        // set the JWT middleware
        $this->middleware('jwt.auth', [
            'except' => ['all']]);

        $this->tags = $tags;
    }

    public function all()
    {
        // get the tags
        $tags = $this->tags->all();

        return $this->respond([
            'tags' => $tags->toArray()]);
    }

    public function createTag(Request $request)
    {
        $tag = $request->input('tag');

        // check if request is empty
        if (empty($tag)) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Tag is needed.']);
        }

        // create tag
        $tag = $this->tags->create($tag);

        // return the created tag
        return $this->respond([
            'tag' => $tag->toArray()]);
    }
}
