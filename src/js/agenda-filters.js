/**
 * Agenda page — client-side track, topic, and format session filters.
 */
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('[data-seminars-agenda-filters]').forEach(function (root) {
		var scope = root.closest('.seminars-agenda');
		if (!scope) {
			return;
		}

		var sessions = scope.querySelectorAll('.seminars-agenda-session[data-agenda-track]');
		if (!sessions.length) {
			return;
		}

		var status = root.querySelector('[data-seminars-agenda-filter-status]');
		var countNode = root.querySelector('[data-seminars-agenda-filter-count]');
		var emptyState = root.querySelector('[data-seminars-agenda-filter-empty]');
		var activeTrack = 'all';
		var activeTopic = 'all';
		var activeFormat = 'all';

		function setButtonState(button, isActive) {
			button.classList.toggle('is-active', isActive);
			button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
		}

		function syncGroup(groupName, value) {
			root.querySelectorAll('[data-seminars-agenda-filter="' + groupName + '"]').forEach(function (button) {
				setButtonState(button, button.getAttribute('data-filter-value') === value);
			});
		}

		function trackBlocks() {
			return scope.querySelectorAll('.seminars-agenda-track');
		}

		function dayGroups() {
			return scope.querySelectorAll('.seminars-agenda-day-group');
		}

		function sessionMatches(session) {
			var track = session.getAttribute('data-agenda-track') || '';
			var topic = session.getAttribute('data-agenda-topic') || '';
			var format = session.getAttribute('data-agenda-format') || '';
			var trackOk = activeTrack === 'all' || track === activeTrack;
			var topicOk = activeTopic === 'all' || topic === activeTopic;
			var formatOk = activeFormat === 'all' || format === activeFormat;
			return trackOk && topicOk && formatOk;
		}

		function filterLabel(group, value) {
			var button = root.querySelector(
				'[data-seminars-agenda-filter="' + group + '"][data-filter-value="' + value + '"]'
			);
			return button ? button.textContent.trim() : '';
		}

		function applyFilters() {
			var visibleCount = 0;

			sessions.forEach(function (session) {
				var show = sessionMatches(session);
				session.hidden = !show;
				if (show) {
					visibleCount += 1;
				}
			});

			trackBlocks().forEach(function (block) {
				var blockSessions = block.querySelectorAll('.seminars-agenda-session[data-agenda-track]');
				var blockVisible = Array.prototype.some.call(blockSessions, function (session) {
					return !session.hidden;
				});
				block.hidden = !blockVisible;
			});

			dayGroups().forEach(function (group) {
				var groupSessions = group.querySelectorAll('.seminars-agenda-session[data-agenda-track]');
				var groupVisible = Array.prototype.some.call(groupSessions, function (session) {
					return !session.hidden;
				});
				group.hidden = !groupVisible;
			});

			if (countNode) {
				countNode.textContent = String(visibleCount);
			}

			if (emptyState) {
				emptyState.hidden = visibleCount > 0;
			}

			if (status) {
				var parts = [];
				if (activeTrack !== 'all') {
					parts.push(filterLabel('track', activeTrack));
				}
				if (activeTopic !== 'all') {
					parts.push(filterLabel('topic', activeTopic));
				}
				if (activeFormat !== 'all') {
					parts.push(filterLabel('format', activeFormat));
				}

				if (!parts.length) {
					status.firstChild.textContent = 'Showing all published sessions. ';
				} else {
					status.firstChild.textContent = 'Showing filtered sessions (' + parts.join(' · ') + '). ';
				}
			}
		}

		root.querySelectorAll('[data-seminars-agenda-filter]').forEach(function (button) {
			button.addEventListener('click', function () {
				var group = button.getAttribute('data-seminars-agenda-filter');
				var value = button.getAttribute('data-filter-value') || 'all';

				if (group === 'track') {
					activeTrack = value;
					syncGroup('track', value);
				} else if (group === 'topic') {
					activeTopic = value;
					syncGroup('topic', value);
				} else if (group === 'format') {
					activeFormat = value;
					syncGroup('format', value);
				}

				applyFilters();
			});
		});

		applyFilters();
	});
});
