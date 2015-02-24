<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Tags\TagRepositoryInterface;

/**
 * Class ApiTagsController
 * @package Journal\Http\Controllers\Api
 */
class ApiTagsController extends ApiController
{
    /**
     * Get all tags saved
     *
     * @param TagRepositoryInterface $tagsRepository
     * @return mixed
     */
    public function getAllTags(TagRepositoryInterface $tagsRepository)
    {
        return $this->respond([
            'data' => [
                'tags' => $tagsRepository->all()]]);
    }
}
