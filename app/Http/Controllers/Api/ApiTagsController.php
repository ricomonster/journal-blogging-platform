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
        // validate
        $messages = $this->tags->validateTags($request->all());

        // check if there are errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // check if there's a given slug because we're going to use that or
        // validate if the given slug is valid else we're going to generate a
        // slug using the name of the tag.
        $string = (empty($request->input('slug'))) ?
            $request->input('name') : $request->input('slug');

        // generate the slug
        $slug = $this->tags->generateSlugUrl($string);

        // create tag
        $tag = $this->tags->create(
            $request->input('name'),
            $request->input('image_url'),
            $request->input('description'),
            $slug);

        // return the created tag
        return $this->respond([
            'tag' => $tag->toArray()]);
    }

    public function deleteTag(Request $request)
    {
        // check if the tag_id is present
        if (empty($request->input('tag_id'))) {
            // return an error message
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Tag ID is required.']);
        }

        // check if the tag exists
        $tag = $this->tags->findById($request->input('tag_id'));

        if (empty($tag)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'Tag does not exists.']);
        }

        // deactivate tag
        $this->tags->deactivate($request->input('tag_id'));

        return $this->respond(['error' => false]);
    }

    public function getTag(Request $request)
    {
        // check if the tag_id is present
        if (empty($request->input('tag_id'))) {
            // return an error message
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Tag ID is required.']);
        }

        // check if the tag exists
        $tag = $this->tags->findById($request->input('tag_id'));

        if (empty($tag)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'Tag does not exists.']);
        }

        // return the tag
        return $this->respond([
            'tag' => $tag->toArray()]);
    }

    public function update(Request $request)
    {
        $tagId = $request->input('tag_id');

        // check if it is empty or set
        if (empty($tagId)) {
            // return an error message
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError(['message' => 'Tag ID is required.']);
        }

        // check if the tag exists
        $tag = $this->tags->findById($tagId);

        if (empty($tag)) {
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError(['message' => 'Tag does not exists.']);
        }

        // validate
        $messages = $this->tags->validateTags($request->all());

        // check if there are errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // check if there's a given slug because we're going to use that or
        // validate if the given slug is valid else we're going to generate a
        // slug using the name of the tag.
        $string = (empty($request->input('slug'))) ?
            $request->input('name') : $request->input('slug');

        // generate the slug
        $slug = $this->tags->generateSlugUrl($string, $tag->id);

        // update
        $tag = $this->tags->update(
            $tag->id,
            $request->input('name'),
            $slug,
            $request->input('image_url'));

        // return the updated tag details
        return $this->respond(['tag' => $tag->toArray()]);
    }
}
