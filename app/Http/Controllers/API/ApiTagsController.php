<?php
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\API\ApiController;
use Journal\Repositories\Tag\TagRepositoryInterface;

/**
 * Class ApiTagsController
 * @package Journal\Http\Controllers\API
 */
class ApiTagsController extends ApiController
{
    protected $tags;

    public function __construct(TagRepositoryInterface $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Creates the tag based on the given request.
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        // check if title is given
        if (empty($request->input('title'))) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::TAG_TITLE_REQUIRED
                ]);
        }

        // create the tag
        $tag = $this->tags->create($request->all());

        // return
        return $this->respond([
            'tag' => $tag->toArray()
        ]);
    }

    /**
     * Kinda deletes a given tag but in reality, we're just setting the selected
     * tag to inactive.
     *
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        // check first if the tag_id is given
        if (!$request->input('tag_id')) {
            // return an error
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::TAG_ID_REQUIRED
                ]);
        }

        // find the tag
        $tag = $this->tags->findById($request->input('tag_id'));

        // check if it exists
        if (empty($tag)) {
            // return an error
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::TAG_NOT_FOUND
                ]);
        }

        // delete tag
        $this->tags->setToInactive($tag->id);

        return $this->respond([
            'error' => false
        ]);
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
                'tag' => $tag->toArray()
            ]);
        }

        // return all tagss
        $tags = $this->tags->all();

        return $this->respond([
            'tags' => $tags->toArray()]);
    }

    /**
     * Updates the given tag based on the given parameters.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        // check first if the tag_id is given
        if (!$request->input('tag_id')) {
            // return an error
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::TAG_ID_REQUIRED
                ]);
        }

        // find the tag
        $tag = $this->tags->findById($request->input('tag_id'));

        // check if it exists
        if (empty($tag)) {
            // return an error
            return $this->setStatusCode(self::NOT_FOUND)
                ->respondWithError([
                    'message' => self::TAG_NOT_FOUND
                ]);
        }

        // check if title is given
        if (empty($request->input('title'))) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError([
                    'message' => self::TAG_TITLE_REQUIRED
                ]);
        }

        // update
        $tag = $this->tags->update($request->all());

        return $this->respond([
            'tag' => $tag->toArray()
        ]);
    }
}
