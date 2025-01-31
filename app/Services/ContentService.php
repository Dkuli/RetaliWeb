<?php
namespace App\Services;

class ContentService
{
    public function storeContent($tourLeader, array $data)
    {
        $content = $tourLeader->contents()->create([
            'group_id' => $tourLeader->current_group_id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
        ]);

        if (isset($data['file'])) {
            $mediaCollection = $data['type'] === 'photo' ? 'photos' : 'videos';

            $content->addMedia($data['file'])
                ->usingName($content->title)
                ->withCustomProperties([
                    'type' => $data['type'],
                    'group_id' => $tourLeader->current_group_id
                ])
                ->storingConversionsOnDisk('media')
                ->withResponsiveImages() // Add responsive images
                ->toMediaCollection($mediaCollection, 'media');
        }

        return $content->load('media');
    }

    public function getGroupContent($group, $type = null, $page = 1)
    {
        $query = $group->contents()
            ->with(['media', 'tourLeader'])
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate(20, ['*'], 'page', $page);
    }
}
