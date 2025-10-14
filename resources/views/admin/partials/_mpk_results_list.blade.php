@forelse($mpkCandidates as $candidate)
    <div class="candidate-list-card">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <div class="candidate-name">
                    <span class="candidate-rank {{ $loop->first ? 'first' : '' }}">{{ $loop->iteration }}</span>
                    {{ $candidate->name_ketua }}
                    @if($candidate->name_wakil)
                        & {{ $candidate->name_wakil }}
                    @endif
                    @if($loop->first)
                        <i class="bi bi-trophy-fill crown-leader"></i>
                    @endif
                </div>
                <div class="progress mt-2">
                    @php
                        $percentage = $totalMpkVotes > 0 ? ($candidate->votes_count / $totalMpkVotes * 100) : 0;
                    @endphp
                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="vote-stats ms-4">
                <div class="vote-count">{{ $candidate->votes_count }}</div>
                <div class="vote-percentage">{{ number_format($percentage, 1) }}%</div>
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <i class="bi bi-people-fill fs-1 text-muted"></i>
        <h5 class="mt-3">Belum Ada Kandidat MPK</h5>
        <p class="text-muted">Data akan tampil setelah kandidat ditambahkan.</p>
    </div>
@endforelse