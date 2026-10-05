<?php

namespace App\Http\Controllers;

use App\Models\Exhibitor;
use App\Models\Show;
use Illuminate\Support\Facades\DB;

class LineupController extends Controller
{
    public function index(Show $show)
    {
        $lineup = $show->lineup()->with('person', 'submissionApplication')->get();

        return view('shows.lineup', compact('show', 'lineup'));
    }

    public function moveUp(Exhibitor $exhibitor)
    {
        return $this->move($exhibitor, -1);
    }

    public function moveDown(Exhibitor $exhibitor)
    {
        return $this->move($exhibitor, 1);
    }

    /**
     * Swap an act with its neighbor, renumbering the lineup 1..n as we go.
     */
    private function move(Exhibitor $exhibitor, int $direction)
    {
        $lineup = $exhibitor->show->lineup()->get()->values();
        $index = $lineup->search(fn ($act) => $act->is($exhibitor));
        $target = $index + $direction;

        if ($index !== false && $target >= 0 && $target < $lineup->count()) {
            $order = $lineup->all();
            [$order[$index], $order[$target]] = [$order[$target], $order[$index]];

            DB::transaction(function () use ($order) {
                foreach ($order as $position => $act) {
                    $act->update(['performance_order' => $position + 1]);
                }
            });
        }

        return redirect()->route('lineup.index', $exhibitor->show);
    }
}
