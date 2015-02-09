<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Tags\TagRepositoryInterface;

class ApiTagsController extends ApiController
{
    public function getAllTags(TagRepositoryInterface $tagsRepository)
    {
        return $this->respond([
            'data' => [
                'tags' => $tagsRepository->all()]]);
    }
}
