<?php

namespace Statamic\Addons\Heartbeats;

use Illuminate\Http\Request;
use Statamic\API\Fieldset;
use Statamic\API\File;
use Statamic\API\YAML;
use Statamic\CP\Publish\ProcessesFields;
use Statamic\Extend\Controller;

class HeartbeatsController extends Controller
{
    use ProcessesFields;

    protected $heartbeatsManager;

    public function __construct(HeartbeatsManager $heartbeatsManager)
    {
        $this->heartbeatsManager = $heartbeatsManager;
    }

    /**
     * Maps to your route definition in routes.yaml
     *
     * @return mixed
     */
    public function index()
    {
        $heartbeats = $this->heartbeatsManager->all();

        return $this->view('index', [
            'title' => 'Heartbeats',
            'heartbeats' => $heartbeats,
        ]);
    }

    public function create()
    {
        $fieldset = $this->getFieldset();

        return $this->view('edit', [
            'title' => trans('addons.Heartbeats::cp.create_heartbeat'),
            'id' => null,
            'fieldset' => $fieldset->toPublishArray(),
            'data' => [],
            'submitUrl' => route('heartbeats.store'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->processFields($this->getFieldset(), $request->get('fields'), false);

        $heartbeat = (new Heartbeat())
            ->setName($data['name'])
            ->setFrequency($data['frequency'])
            ->setUrl($data['url']);


        $this->heartbeatsManager
            ->add($heartbeat)
            ->flush();

        if (!$request->get('continue') || $request->get('new')) {
            $this->success(trans('addons.Heartbeats::cp.saved_successfully'));
        }

        return [
            'success' => true,
            'redirect' => route('heartbeats.index'),
            'message' => trans('addons.Heartbeats::cp.saved_successfully'),
        ];
    }

    public function update($heartbeatId, Request $request)
    {
        $data = $this->processFields($this->getFieldset(), $request->get('fields'), false);

        $this->heartbeatsManager->remove($heartbeatId);

        $heartbeat = (new Heartbeat())
            ->setName($data['name'])
            ->setFrequency($data['frequency'])
            ->setUrl($data['url']);
        $this->heartbeatsManager
            ->add($heartbeat)
            ->flush();

        return [
            'success' => true,
            'redirect' => route('heartbeats.index'),
            'message' => trans('addons.Heartbeats::cp.updated_successfully'),
        ];
    }

    public function edit($heartbeatId)
    {
        $heartbeat = $this->heartbeatsManager->get($heartbeatId);

        $fieldset = $this->getFieldset();

        return $this->view('edit', [
            'id' => $heartbeatId,
            'title' => trans('addons.Heartbeats::cp.update_heartbeat'),
            'submitUrl' => route('heartbeats.update', $heartbeatId),
            'fieldset' => $fieldset->toPublishArray(),
            'data' => $this->preProcessWithBlankFields($fieldset, $heartbeat->toArray()),
        ]);
    }

    public function delete($heartbeatId)
    {
        $this->heartbeatsManager->remove($heartbeatId);
        $this->heartbeatsManager->flush();

        return redirect()
            ->route('heartbeats.index')
            ->with([
                'success' => trans('addons.Heartbeats::cp.deleted_successfully'),
            ]);
    }

    private function getFieldset()
    {
        $yaml = File::get($this->getDirectory() . "/resources/fieldsets/heartbeats.yaml");
        $fields = YAML::parse($yaml);

        $namespace = 'fieldsets/heartbeats';
        $localizedFields = collect($fields)->map(function ($field, $name) use ($namespace) {
            $label = ($name === 'locale') ? trans('cp.locale') : $this->trans(sprintf('%s.%s', $namespace, $name));
            $field['display'] = $label;
            $instructionsKey = sprintf('%s.%s_%s', $namespace, $name, 'instructions');
            $instructions = $this->trans($instructionsKey);
            if ($instructions !== 'addons.Heartbeats::' . $instructionsKey) {
                $field['instructions'] = $instructions;
            }

            return $field;
        });

        $contents['sections']['main']['fields'] = $localizedFields->toArray();

        return Fieldset::create('heartbeat', $contents);
    }
}
