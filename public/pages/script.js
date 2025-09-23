
// Modal de recompensas
function createRewardModal() {
	if (document.getElementById('reward-modal')) return;
	const modal = document.createElement('div');
	modal.id = 'reward-modal';
	modal.className = 'reward-modal';
	modal.style.display = 'none';
	modal.innerHTML = `
		<div class="reward-modal-content">
			<span class="reward-modal-close" id="reward-modal-close">&times;</span>
			<div class="reward-modal-icon" id="reward-modal-icon"></div>
			<textarea class="reward-modal-textarea" id="reward-modal-textarea" readonly>Aquí va la descripción de la recompensa</textarea>
		</div>
	`;
	document.body.appendChild(modal);
	document.getElementById('reward-modal-close').onclick = closeRewardModal;
}

function showRewardModal(icon) {
	createRewardModal();
	document.getElementById('reward-modal').style.display = 'flex';
	document.getElementById('reward-modal-icon').textContent = icon;
	document.getElementById('reward-modal-textarea').value = 'Aquí va la descripción de la recompensa';
}

function closeRewardModal() {
	document.getElementById('reward-modal').style.display = 'none';
}

window.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('.reward-icon').forEach(function(el) {
		el.addEventListener('click', function() {
			showRewardModal(el.getAttribute('data-icon'));
		});
	});
});