// ==================== NOTES FUNCTIONS ====================

// Load notes for a contact
function loadNotes(contactId) {
  fetch(`load_notes.php?contact_id=${contactId}`)
    .then(res => res.text())
    .then(data => {
      document.getElementById('notes').innerHTML = data;
    })
    .catch(error => console.error('Error loading notes:', error));
}

// Add a new note
function addNote(contactId) {
  const text = document.getElementById('noteText').value;

  if (!text.trim()) {
    alert("Please enter a note.");
    return;
  }

  fetch('add_note.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `contact_id=${contactId}&comment=${encodeURIComponent(text)}`
  })
  .then(response => response.json())  // Expecting JSON from PHP
  .then(data => {
    if (data.status === 'success') {
      document.getElementById('noteText').value = '';  // Clear input
      loadNotes(contactId);  // Reload notes
    } else {
      alert(data.message);  // Show error from PHP
    }
  })
  .catch(error => {
    console.error("Error adding note:", error);
    alert("There was an error adding the note. Please try again.");
  });
}

// ASSIGN CONTACT
function assignToMe(contactId) {
    fetch('assign_contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Failed to assign contact.');
    })
    .catch(() => alert('Error assigning contact.'));
}

// SWITCH CONTACT TYPE
function switchType(contactId, type) {
    fetch('switch_type.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}&type=${encodeURIComponent(type)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
        else alert(data.message || 'Failed to switch type.');
    })
    .catch(() => alert('Error switching type.'));
}

// NOTES FUNCTIONS (unchanged)
function loadNotes(contactId) {
    fetch(`load_notes.php?contact_id=${contactId}`)
        .then(res => res.text())
        .then(data => {
            document.getElementById('notes').innerHTML = data;
        })
        .catch(error => console.error('Error loading notes:', error));
}

function addNote(contactId) {
    const text = document.getElementById('noteText').value;
    if (!text.trim()) { alert("Please enter a note."); return; }

    fetch('add_note.php', {
        method: 'POST',
        headers: { 'Content-Type':'application/x-www-form-urlencoded' },
        body: `contact_id=${contactId}&comment=${encodeURIComponent(text)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('noteText').value = '';
            loadNotes(contactId);
        } else alert(data.message);
    })
    .catch(() => alert('Error adding note.'));
}

// INIT NOTES
document.addEventListener('DOMContentLoaded', () => {
    const notesContainer = document.getElementById('notes');
    if (notesContainer) {
        const contactId = notesContainer.dataset.contactId;
        if (contactId) loadNotes(contactId);
    }
});
