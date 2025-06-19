<?php

namespace App\Services;



use App\Models\Comment;
use App\Models\Project;
use App\Models\User;
use DB;
use Doctrine\DBAL\Exception;

class LikeService
{
    // Add your service methods here
    /**
     * @throws \Throwable
     */
    public function Liked(Project|Comment $object, User $user )
    {

      try{
          db::beginTransaction();
          $existingLike = $object->likes()
              ->where('user_id', $user->id)
              ->first();
          if($existingLike){
              return response()->json([
                  'status' => false,
                  'message'=>'Already Liked'
              ],400);
          }
          $object->likes()->create(['user_id' => $user->id]);
          $object->increment('likes');
          db::commit();
          return response()->json([
              'status' => true,
              'message'=>'Liked successfully'
          ]);
      }
      catch (Exception $exception){
          db::rollBack();
          return response()->json([
              'status' => false,
              'message'=>$exception->getMessage()
          ]);
      }

    }

    /**
     * @throws \Throwable
     */
    public function DeleteLike(Project|Comment $object, User $user): \Illuminate\Http\JsonResponse
    {
        try{
            db::beginTransaction();
            $existingLike = $object->likes()
                ->where('user_id', $user->id)
                ->first();
            if(!$existingLike){
                return response()->json([
                    'status' => false,
                    'message'=>'You dont Liked it at all'
                ],400);
            }
            $existingLike->delete();
            $object->decrement('likes');
            db::commit();
            return response()->json([
                'status' => true,
                'message'=>'Like deleted  successfully'
            ]);
        }
        catch (Exception $exception){
            db::rollBack();
            return response()->json([
                'status' => false,
                'message'=>$exception->getMessage()
            ]);
        }

    }
}
