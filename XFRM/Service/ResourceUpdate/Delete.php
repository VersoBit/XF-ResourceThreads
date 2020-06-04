<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceUpdate;

class Delete extends XFCP_Delete
{
    public function delete($type, $reason = '')
    {
        $result = parent::delete($type, $reason);

        $update = $this->update;

        $this->deleteDiscussionThreadPost($update);

        return $result;
    }

    protected function deleteDiscussionThreadPost($update)
    {
        // TODO: find more solid way of finding the update's post in discussion thread
        $updateUrl = '%resources/'. strtolower($update->title) .'.'. $update->Resource->resource_id .'/update/'. $update->resource_update_id .'/%';
        $post = \XF::finder('XF:Post')->where([
            'thread_id' => $update->Resource->discussion_thread_id,
            'user_id' => $update->Resource->user_id,
            ['message', 'LIKE', $updateUrl]
        ])->fetchOne();

        // Delete resource update's post if was unapproved
        if($post->message_state == 'moderated'){
            /** @var \XF\Service\Post\Deleter $postDeleter */
            $postDeleter = $this->service('XF:Post\Deleter', $post);
            $postDeleter->delete('soft', \XF::phrase('xfrm_resource_update').' deleted');
        }
    }
}