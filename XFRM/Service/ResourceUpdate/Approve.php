<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceUpdate;

class Approve extends XFCP_Approve
{
    protected function onApprove()
    {
        parent::onApprove();

        $update = $this->update;

        $this->approveDiscussionThreadPost($update);
    }

    protected function approveDiscussionThreadPost($update)
    {
        // TODO: find more solid way of finding the update's post in discussion thread
        $updateUrl = '%resources/'. strtolower($update->title) .'.'. $update->Resource->resource_id .'/update/'. $update->resource_update_id .'/%';
        $post = \XF::finder('XF:Post')->where([
            'thread_id' => $update->Resource->discussion_thread_id,
            'user_id' => $update->Resource->user_id,
            ['message', 'LIKE', $updateUrl]
        ])->fetchOne();

        // Approve resource update's associated post if unapproved
        if($post AND $post->message_state == 'moderated'){
            /** @var \XF\Service\Post\Approver $postApprover */
            $postApprover = \XF::service('XF:Post\Approver', $post);
            $postApprover->approve();
        }
    }
}