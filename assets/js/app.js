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

  if (!text.trim()) {
    alert("Please enter a note.");
    return;
  }

  fetch('add_note.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: `contact_id=${contactId}&comment=${encodeURIComponent(text)}`
  })
  .then(response => response.json())  // Expecting JSON response
  .then(data => {
    if (data.status === 'success') {
      document.getElementById('noteText').value = '';  // Clear the input field
      loadNotes(contactId);  // Reload the notes section
    } else {
      alert(data.message);  // Show the error message
    }
  })
  .catch(error => {
    console.error("Error adding note:", error);
    alert("There was an error adding the note. Please try again.");
  });
}

function assignToMe(contactId) {
  fetch(`assign_contact.php?id=${contactId}`);
  location.reload();
}

function switchType(contactId, type) {
  fetch(`switch_type.php?id=${contactId}&type=${type}`);
  location.reload();
}
