@extends('layouts.frontend')

@php
    $site_name = App\Models\WebSetting::where('key', 'site_name')->value('value') ?? 'XTube';
    $page_title = $video->title ?? 'Undefined' . ' - ' . $site_name;
@endphp

@section('title', $page_title)

@section('content')
    <!-- Rectangle Banner Large (Before Video) -->
    @include('components.banner', [
        'type' => 'banner_rectangle_small',
        'class' => 'mb-6 flex w-full justify-center justify-center',
    ])

    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Video Section -->
            <div class="lg:col-span-2">
                <!-- Better UX Version with localStorage tracking -->

                @php
                    $offerLink1 = App\Helpers\AffiliateHelper::render('offer_link_1');
                    $offerLink2 = App\Helpers\AffiliateHelper::render('offer_link_2');
                    $redirectLink = $offerLink1 ?: $offerLink2;
                @endphp

                @if ($video->type === 'url' && $video->cloudinary_url)
                    <div class="relative bg-black rounded-lg overflow-hidden aspect-video" id="videoContainer">
                        <!-- Click Overlay -->
                        <div id="clickOverlay" class="absolute inset-0 z-10 hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex items-center justify-center">
                                <div class="text-center p-6 bg-black/50 backdrop-blur-sm rounded-lg">
                                    <svg class="w-16 h-16 text-white mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <button id="playButton"
                                        class="px-8 py-3 bg-blue-600 cursor-pointer hover:bg-blue-700 text-white font-semibold rounded-full transition">
                                        Play Video
                                    </button>
                                    <p class="text-gray-300 text-xs mt-3">Support us by visiting our sponsor</p>
                                </div>
                            </div>
                        </div>

                        <!-- Video Player -->
                        <video id="videoPlayer" class="w-full h-full" controls
                            poster="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : '' }}" preload="metadata"
                            playsinline webkit-playsinline>
                            <source src="{{ $video->cloudinary_url }}" type="video/mp4">
                        </video>
                    </div>

                    <script>
                        (function() {
                            const videoId = '{{ $video->slug }}';
                            const storageKey = `video_clicked_${videoId}`;
                            const redirectLink = '{{ $redirectLink }}';
                            const clickOverlay = document.getElementById('clickOverlay');
                            const playButton = document.getElementById('playButton');
                            const videoPlayer = document.getElementById('videoPlayer');

                            let clickTimer = null;

                            // Check if user already clicked (reset setiap 3 detik)
                            const hasClickedToday = () => {
                                const clicked = localStorage.getItem(storageKey);
                                if (!clicked) return false;

                                const clickedTime = parseInt(clicked);
                                const now = Date.now();

                                // Reset jika lebih dari 3 detik
                                const timeDiff = now - clickedTime;
                                if (timeDiff > 3000) { // 3 detik dalam milliseconds
                                    localStorage.removeItem(storageKey);
                                    return false;
                                }

                                return true;
                            };

                            const setClick = () => {
                                const now = Date.now();
                                localStorage.setItem(storageKey, now.toString());

                                // Set timer untuk reset setelah 3 detik
                                clearTimeout(clickTimer);
                                clickTimer = setTimeout(() => {
                                    localStorage.removeItem(storageKey);
                                    console.log('Reset setelah 3 detik');
                                }, 3000);
                            };

                            // Show overlay only if haven't clicked and redirect link exists
                            if (!hasClickedToday() && redirectLink) {
                                clickOverlay.classList.remove('hidden');

                                playButton.addEventListener('click', function() {
                                    // Open offer link
                                    window.open(redirectLink, '_blank');

                                    // Save to localStorage
                                    setClick(); // Panggil fungsi setClick

                                    // Show thank you message
                                    this.innerHTML = '✓ Thank you!';
                                    this.disabled = true;

                                    // Remove overlay and play video after 1 second
                                    setTimeout(() => {
                                        clickOverlay.remove();
                                        videoPlayer.play().catch(err => console.log('Play prevented:', err));
                                    }, 1000);
                                });

                                // Prevent play before click
                                videoPlayer.addEventListener('play', function(e) {
                                    if (!hasClickedToday() && clickOverlay && !clickOverlay.classList.contains('hidden')) {
                                        e.preventDefault();
                                        videoPlayer.pause();

                                        // Optional: Show message to click button first
                                        playButton.style.animation = 'pulse 0.5s ease-in-out';
                                        setTimeout(() => {
                                            playButton.style.animation = '';
                                        }, 500);
                                    }
                                });

                                // Juga prevent context menu dan right click
                                videoPlayer.addEventListener('contextmenu', function(e) {
                                    e.preventDefault();
                                });
                            } else {
                                // Jika sudah klik atau tidak ada redirect link, auto play
                                setTimeout(() => {
                                    videoPlayer.play().catch(err => console.log('Auto-play prevented:', err));
                                }, 1000);
                            }

                            // Handle video ended - reset state
                            videoPlayer.addEventListener('ended', function() {
                                // Optional: Reset setelah video selesai
                                localStorage.removeItem(storageKey);
                            });

                        })();
                    </script>
                @elseif($video->type === 'iframe' && $video->iframe)
                    <div class="video-embed-container">
                        {!! $video->iframe !!}
                    </div>
                @else
                    <div
                        class="w-full aspect-video bg-gray-200 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                        <p class="text-gray-600 dark:text-gray-400">Video tidak tersedia</p>
                    </div>
                @endif

                <!-- Video Info -->
                <div class="mt-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $video->title }}
                    </h1>

                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                            <span>{{ $video->formatted_views }} views</span>
                            <span>•</span>
                            <span>{{ $video->published_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Like Button -->
                            <button onclick="likeVideo()" id="likeButton"
                                class="flex items-center cursor-pointer active:scale-95 space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <svg id="likeIcon" class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                    </path>
                                </svg>
                                <span id="likeCount"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $video->likes }}</span>
                            </button>

                            <!-- Share Button -->
                            <button onclick="shareVideo()"
                                class="flex items-center cursor-pointer active:scale-95 space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Share</span>
                            </button>
                        </div>
                    </div>

                    <!-- Category & Tags -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                            style="background-color: {{ $video->category->color }}20; color: {{ $video->category->color }}">
                            {{ $video->category->name }}
                        </span>

                        @if ($video->tags)
                            @foreach ($video->tags as $tag)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $video->description ?: 'No description available.' }}
                        </p>
                    </div>

                    <!-- Native Banner below description -->
                    <div class="mt-6">
                        @include('components.banner', ['type' => 'banner_native_1'])
                    </div>
                </div>
            </div>

            <!-- Related Videos Sidebar -->
            <div class="lg:col-span-1">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Related Videos</h2>

                <div class="space-y-3">
                    @forelse($relatedVideos as $related)
                        <a href="{{ route('watch', $related->slug) }}" class="flex gap-2 group">
                            <!-- Thumbnail -->
                            <div
                                class="relative w-40 aspect-video bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                                @if ($related->thumbnail)
                                    <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <div
                                    class="absolute bottom-1 right-1 bg-black bg-opacity-80 text-white text-xs px-1.5 py-0.5 rounded">
                                    {{ $related->formatted_duration }}
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 mb-1">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $related->category->name }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $related->formatted_views }} views • {{ $related->published_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-600 dark:text-gray-400">No related videos found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Native Banner 1 -->
    @include('components.banner', ['type' => 'banner_native_1', 'class' => 'mb-6 flex justify-center'])

    <!-- Comments Section -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            Comments ({{ $video->comments()->parentOnly()->count() }})
        </h3>

        <!-- Comment Form -->
        @auth
            <form onsubmit="submitComment(event)" class="mb-6">
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <textarea id="commentInput" placeholder="Add a comment..." rows="2"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500"
                            required></textarea>
                        <div class="flex justify-end mt-2 space-x-2">
                            <button type="button" onclick="clearCommentForm()"
                                class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm bg-blue-600 cursor-pointer text-white rounded-lg hover:bg-blue-700 transition">
                                Comment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg text-center">
                <p class="text-gray-600 dark:text-gray-400 mb-2">Sign in to comment</p>
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                    Login
                </a>
            </div>
        @endauth

        <!-- Comments List -->
        <div id="commentsList" class="space-y-4">
            @foreach ($video->comments()->parentOnly()->with('user', 'replies.user')->latest()->get() as $comment)
                @include('components.comment-item', ['comment' => $comment])
            @endforeach
        </div>
    </div>

    <script>
        function shareVideo() {
            const url = window.location.href;
            const title = '{{ $video->title }}';

            // Try Web Share API first (mobile)
            if (navigator.share) {
                navigator.share({
                    title: title,
                    url: url
                }).catch(err => {
                    console.log('Share cancelled or error:', err);
                });
            } else if (navigator.clipboard && navigator.clipboard.writeText) {
                // Fallback: Copy to clipboard (desktop)
                navigator.clipboard.writeText(url)
                    .then(() => {
                        alert('✅ Link copied to clipboard!');
                    })
                    .catch(err => {
                        console.error('Copy failed:', err);
                        // Final fallback: show modal with URL
                        showShareModal(url);
                    });
            } else {
                // Browser doesn't support clipboard API
                showShareModal(url);
            }
        }

        function showShareModal(url) {
            const modal = `
                <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeShareModal()">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full" onclick="event.stopPropagation()">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Share Video</h3>
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg mb-4">
                            <input type="text" value="${url}" readonly
                                class="w-full bg-transparent text-sm text-gray-900 dark:text-white outline-none"
                                onclick="this.select()">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button onclick="closeShareModal()" class="px-4 cursor-pointer py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                Close
                            </button>
                            <button onclick="copyFromModal('${url}')" class="px-4 py-2 bg-blue-600 cursor-pointer text-white rounded-lg hover:bg-blue-700">
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modal);
        }

        function closeShareModal() {
            document.getElementById('shareModal')?.remove();
        }

        // Auto-play video when page loads
        function copyFromModal(url) {
            const input = document.querySelector('#shareModal input');
            input.select();
            document.execCommand('copy');
            alert('✅ Link copied!');
            closeShareModal();
        }

        // Like Video Function
        function likeVideo() {
            const videoSlug = '{{ $video->slug }}';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                console.error('CSRF token not found!');
                alert('⚠️ Security token missing. Please refresh the page.');
                return;
            }

            const likeButton = document.getElementById('likeButton');
            const likeCountEl = document.getElementById('likeCount');
            const likeIcon = document.getElementById('likeIcon');

            if (likeButton) likeButton.disabled = true;

            fetch(`/videos/${videoSlug}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.status === 419) {
                        throw new Error('Session expired. Please refresh the page.');
                    }
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update like count (with null check)
                        if (likeCountEl) {
                            likeCountEl.textContent = data.likes;
                        }

                        // Change icon to filled (with null check)
                        if (likeIcon) {
                            if (data.liked) {
                                likeIcon.setAttribute('fill', 'currentColor');
                                likeIcon.classList.add('text-blue-600', 'dark:text-blue-400');
                            } else {
                                likeIcon.setAttribute('fill', 'none');
                                likeIcon.classList.remove('text-blue-600', 'dark:text-blue-400');
                            }
                        }

                        console.log('Like success:', data);
                    }
                })
                .catch(error => {
                    console.error('Like error:', error);
                    alert('❌ ' + error.message);
                })
                .finally(() => {
                    // Re-enable button
                    if (likeButton) likeButton.disabled = false;
                });
        }

        // Submit Comment
        function submitComment(event) {
            event.preventDefault();
            const content = document.getElementById('commentInput').value;
            const videoId = {{ $video->id }};

            fetch(`/videos/${videoId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('[name="_token"]')?.value,
                    },
                    body: JSON.stringify({
                        content: content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add comment to list
                        document.getElementById('commentsList').insertAdjacentHTML('afterbegin', data.html);
                        // Clear form
                        document.getElementById('commentInput').value = '';
                        // Update count
                        updateCommentCount();
                    }
                })
                .catch(error => console.error('Comment error:', error));
        }

        // Submit Reply
        function submitReply(event, parentId) {
            event.preventDefault();
            const form = event.target;
            const content = form.querySelector('input').value;
            const videoId = {{ $video->id }};

            fetch(`/videos/${videoId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('[name="_token"]')?.value,
                    },
                    body: JSON.stringify({
                        content: content,
                        parent_id: parentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add reply to list
                        document.getElementById(`replies${parentId}`).insertAdjacentHTML('beforeend', data.html);
                        // Clear and hide form
                        form.reset();
                        toggleReplyForm(parentId);
                    }
                })
                .catch(error => console.error('Reply error:', error));
        }

        // Like Comment
        function likeComment(commentId) {
            fetch(`/comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('[name="_token"]')?.value,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`commentLikes${commentId}`).textContent = data.likes;
                        const icon = document.getElementById(`commentLikeIcon${commentId}`);
                        if (data.liked) {
                            icon.setAttribute('fill', 'currentColor');
                            icon.classList.add('text-blue-600');
                        } else {
                            icon.setAttribute('fill', 'none');
                            icon.classList.remove('text-blue-600');
                        }
                    }
                })
                .catch(error => {
                    if (error.message.includes('401')) {
                        alert('Please login to like comments');
                    }
                });
        }

        // Delete Comment
        function deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;

            fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('[name="_token"]')?.value,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`[data-comment-id="${commentId}"]`).remove();
                        updateCommentCount();
                    }
                })
                .catch(error => console.error('Delete error:', error));
        }

        // Edit Comment
        function editComment(commentId) {
            const contentEl = document.getElementById(`commentContent${commentId}`);
            const currentContent = contentEl.textContent;

            const input = document.createElement('textarea');
            input.value = currentContent;
            input.className =
                'w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white';

            contentEl.replaceWith(input);
            input.focus();

            input.addEventListener('blur', function() {
                const newContent = this.value;

                fetch(`/comments/${commentId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                document.querySelector('[name="_token"]')?.value,
                        },
                        body: JSON.stringify({
                            content: newContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const p = document.createElement('p');
                            p.id = `commentContent${commentId}`;
                            p.className = 'text-sm text-gray-700 dark:text-gray-300';
                            p.textContent = newContent;
                            this.replaceWith(p);
                        }
                    });
            });
        }

        // Toggle Reply Form
        function toggleReplyForm(commentId) {
            const form = document.getElementById(`replyForm${commentId}`);
            form.classList.toggle('hidden');
        }

        // Clear Comment Form
        function clearCommentForm() {
            document.getElementById('commentInput').value = '';
        }

        // Update Comment Count
        function updateCommentCount() {
            const count = document.querySelectorAll('.comment-item').length;
            document.querySelector('h3').textContent = `Comments (${count})`;
        }

        // Auto-play video when page loads
        document.addEventListener('DOMContentLoaded', function() {
                    // const video = document.getElementById('videoPlayer');
                    // if (video) {
                    //     video.play().catch(err => console.log('Auto-play prevented:', err));
                    // }
                    const videoPlayer = document.getElementById('videoPlayer');
                    const loading = document.getElementById('loadingIndicator');

                    if (videoPlayer) {
                        // Show loading when video is buffering
                        videoPlayer.addEventListener('waiting', function() {
                            loading.classList.remove('hidden');
                        });

                        videoPlayer.addEventListener('canplay', function() {
                            loading.classList.add('hidden');
                        });

                        videoPlayer.addEventListener('error', function(e) {
                            console.error('Video error:', e);
                            loading.classList.add('hidden');

                            // Fallback to iframe jika video URL gagal
                            @if ($video->type === 'iframe' && $video->iframe)
                                document.querySelector('.bg-black').innerHTML = `{!! $video->iframe !!}`;
                            @endif
                        });

                        // Auto play prevention dengan user interaction
                        videoPlayer.addEventListener('click', function() {
                            if (videoPlayer.paused) {
                                videoPlayer.play().catch(e => console.log('Auto-play prevented'));
                            }
                        });
                    });
    </script>
@endsection
