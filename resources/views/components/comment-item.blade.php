{{-- Comment Item Component --}}
<div class="comment-item mb-4" data-comment-id="{{ $comment->id }}">
    <div class="flex space-x-3">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                {{ substr($comment->user->name, 0, 1) }}
            </div>
        </div>

        <!-- Comment Content -->
        <div class="flex-1">
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-semibold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</h4>
                    <span
                        class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-700 dark:text-gray-300" id="commentContent{{ $comment->id }}">
                    {{ $comment->content }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-4 mt-2 text-sm">
                <!-- Like Button -->
                <button onclick="likeComment({{ $comment->id }})"
                    class="flex items-center space-x-1 cursor-pointer active:scale-95 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                    <svg id="commentLikeIcon{{ $comment->id }}"
                        class="w-4 h-4 {{ $comment->isLikedBy(auth()->id()) ? 'fill-current text-blue-600' : '' }}"
                        fill="{{ $comment->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                        </path>
                    </svg>
                    <span id="commentLikes{{ $comment->id }}">{{ $comment->likes }}</span>
                </button>

                <!-- Reply Button -->
                @auth
                    <button onclick="toggleReplyForm({{ $comment->id }})"
                        class="text-gray-600 dark:text-gray-400 cursor-pointer active:scale-95 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        Reply
                    </button>
                @endauth

                <!-- Edit/Delete (Own comments only) -->
                @auth
                    @if (auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                        <button onclick="editComment({{ $comment->id }})"
                            class="text-gray-600 dark:text-gray-400 hover:text-blue-600 active:scale-95 dark:hover:text-blue-400 transition">
                            Edit
                        </button>
                        <button onclick="deleteComment({{ $comment->id }})"
                            class="text-gray-600 dark:text-gray-400 cursor-pointer active:scale-95 hover:text-red-600 dark:hover:text-red-400 transition">
                            Delete
                        </button>
                    @endif
                @endauth
            </div>

            <!-- Reply Form (Hidden by default) -->
            @auth
                <div id="replyForm{{ $comment->id }}" class="hidden mt-3">
                    <form onsubmit="submitReply(event, {{ $comment->id }})" class="flex space-x-2">
                        <input type="text" placeholder="Write a reply..."
                            class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500"
                            required>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 cursor-pointer active:scale-95 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                            Reply
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Replies -->
            @if ($comment->replies->count() > 0)
                <div class="mt-3 space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-700"
                    id="replies{{ $comment->id }}">
                    @foreach ($comment->replies as $reply)
                        @include('components.comment-item', ['comment' => $reply])
                    @endforeach
                </div>
            @else
                <div id="replies{{ $comment->id }}"
                    class="mt-3 space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-700"></div>
            @endif
        </div>
    </div>
</div>
