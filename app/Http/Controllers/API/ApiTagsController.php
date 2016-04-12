<?php
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;
use Journal\Repositories\Tag\TagRepositoryInterface;

class ApiTagsController extends ApiController
{
    protected $tags;

    public function __construct(TagRepositoryInterface $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get tags saved in the database.
     *
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        // check if tag_id exists
        if ($request->input('tag_id')) {
            // get the tag
            $tag = $this->tags->findById($request->input('tag_id'));

            // check if it exists
            if (empty($tag)) {
                return $this->setStatusCode(self::NOT_FOUND)
                    ->respondWithError(['message' => self::TAG_NOT_FOUND]);
            }

            // return the tags
            return $this->respond([
                'tag' => $tag->toArray()]);
        }

        // return all tagss
        $tags = $this->tags->all();

        return $this->respond([
            'tags' => $tags->toArray()]);
    }
}
