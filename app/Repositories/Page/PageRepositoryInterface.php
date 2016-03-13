<?php //-->
namespace Journal\Repositories\Page;

interface PageRepositoryInterface
{
    public function create();
    public function search();
    public function all();
    public function findById();
    public function findBySlug();
    public function update();
    public function removeAsPage();
    public function setPostAsPage();
    public function setToInactive();
    public function validatePage();
}
