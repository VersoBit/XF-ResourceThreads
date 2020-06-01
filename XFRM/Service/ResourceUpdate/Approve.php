<?php

namespace VersoBit\ResourceThreads\XFRM\Service\ResourceUpdate;

class Approve extends XFCP_Approve
{
    protected function onApprove()
    {
        parent::onApprove();

        $update = $this->update;

        // TODO: find more solid way of finding the update's post in discussion thread
        $post = \XF::finder('XF:Post')->where([
            'thread_id' => $update->Resource->discussion_thread_id,
            'post_date' => $update->post_date,
            'user_id' => $update->Resource->user_id
        ])->fetchOne();

        // Approve resource's associated thread if unapproved
        if($post->message_state == 'moderated'){
            /** @var \XF\Service\Post\Approver $postApprover */
            $postApprover = \XF::service('XF:Post\Approver', $post);
            $postApprover->approve();
        }
    }
}