<!-- Voters -->
<section id="voters" class="card section" style="display:none">
  <h3>Voter Management</h3>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Grade</th>
        <th>Section</th>
        <th>Student ID</th>
        <th>Verified</th>
        <th>Voted</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($v = $voters->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($v['name']) ?></td>
        <td><?= htmlspecialchars($v['grade']) ?></td>
        <td><?= htmlspecialchars($v['section']) ?></td>
        <td><?= htmlspecialchars($v['student_id']) ?></td>
        <td><?= $v['verified'] ? 'Yes' : 'No' ?></td>
        <td><?= $v['hasVoted'] ? 'Yes' : 'No' ?></td>
        <td>
          <?php if(!$v['verified']): ?>
            <a href="?verify=<?= $v['id'] ?>" class="btn" onclick="return confirm('Verify this student?')">Verify</a>
          <?php endif; ?>
          <a href="edit-voter.php?id=<?= $v['id'] ?>" class="btn">Edit</a>
          <a href="?deleteVoter=<?= $v['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this voter?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>
