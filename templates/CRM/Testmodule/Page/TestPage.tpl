<div  >
<table id="options" class="display">
  <thead>
    <tr>
    <th>{ts}Personal Campaign Page Title{/ts}</th>
    <th>{ts}Contribution Page Title{/ts}</th>
    <th>{ts}Number of Contribution{/ts}</th>
    <th>{ts}Amount Raised{/ts}</th>
    <th>{ts}Target Amount{/ts}</th>
    <th>{ts}Status{/ts}</th>
    <th>Action</th>
    
    </tr>
  </thead>
  <tbody>

  {foreach from=$rows item=row}
  <tr id="row_{$row.id}" class="{$row.class}">
    <td>{$row.campaign_title}</td>
    <td>{$row.contribution_page_title}</a></td>
    <td>{$row.num_of_contribution}</td>
    <td>{$row.amount_raised}</td>
    <td>{$row.target_amount}</td>
    <td>{$row.status}</td>
    <td id={$row.id}>{$row.action|replace:'xx':$row.id}
  </tr>
  {/foreach}
  </tbody>
</table>
</div>

