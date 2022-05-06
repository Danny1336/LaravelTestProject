<?php
namespace App\Repositories;
use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository
 *
 * @package App\Repositories
 */
class BlogCategoryRepository extends CoreRepository
{
    public function getModelClass(){
        return Model::class;
    }
    /**
     * @param int $id
     *
     * @return Model
     */
    public function getEdit($id){
        return $this->startConditions()->find($id);
    }

    public function getForComboBox(){
        return $this->startConditions()->all();
    }

}
