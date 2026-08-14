# Review Merge Request

Review a GitLab merge request using `glab`, show findings in chat, then post approved comments.

## Usage

```
/review-merge-request <MR-number or MR-URL>
```

## Steps

1. **Fetch MR info**: Run `glab mr view <MR>` to get title, description, author, branch info.
2. **Fetch the diff**: Run `glab mr diff <MR>` to get all changed files and their diffs.
3. **Fetch diff refs**: Run the following to get the SHAs needed for inline comments:
   ```
   glab api "projects/:fullpath/merge_requests/<MR>" --jq '{base: .diff_refs.base_sha, head: .diff_refs.head_sha, start: .diff_refs.start_sha}'
   ```
4. **Review**: Analyse the diff thoroughly. For each finding, produce two versions:
   - A **Dutch** version to show in the chat to the user.
   - An **English** version to post on GitLab (prepared but not shown unless posting).

   Track the exact **file path** and **new-file line number** for each finding (needed for inline placement).

   Structure each finding as:

```
[N] <file>:<line>
Type: Bug | Security | Style | Performance | Suggestion
Severity: Critical | Major | Minor | Info
---
<uitleg in het Nederlands>

Suggestie:
<concrete fix of code snippet>
```

5. **Present findings**: Show all findings numbered in the chat **in Dutch**. After listing them, ask:
   > "Welke punten wil je als comment plaatsen op de MR? Geef de nummers op (bijv. 1,3,5) of zeg 'alles' of 'geen'."

6. **Post approved comments as draft notes**: Write each comment to a temp JSON file using `note` (not `body`) and post as a draft. Using `-F` form fields does NOT work for nested position params — always use `--input` with `-H "Content-Type: application/json"`:
   ```bash
   cat > /tmp/mr_comment.json << EOF
   {
     "note": "<explanation>\n\n**Suggestion:**\n```php\n<code>\n```",
     "position": {
       "position_type": "text",
       "base_sha": "<base_sha>",
       "start_sha": "<start_sha>",
       "head_sha": "<head_sha>",
       "old_path": "<file>",
       "new_path": "<file>",
       "new_line": <line>
     }
   }
   EOF

   glab api "projects/:fullpath/merge_requests/<MR>/draft_notes" \
     -X POST \
     -H "Content-Type: application/json" \
     --input /tmp/mr_comment.json
   ```

   Always include `old_path` (same value as `new_path` for added/modified files) — without it GitLab ignores the position.

   Verify the comment was placed inline by checking `position != null` in the response.

7. **Submit the review**: Publish all draft notes as a single review batch:
   ```bash
   glab api "projects/:fullpath/merge_requests/<MR>/draft_notes/bulk_publish" -X POST
   ```

8. **Request changes**: The REST endpoint `POST /request_changes` returns 404 on this GitLab instance. Use the GraphQL API instead:
   ```bash
   glab api "graphql" -X POST -H "Content-Type: application/json" --input - << 'EOF'
   {"query": "mutation { mergeRequestRequestChanges(input: { projectPath: \"<project_path>\", iid: \"<MR>\" }) { mergeRequest { id } errors } }"}
   EOF
   ```
   Where `<project_path>` is the full GitLab project path (e.g. `group/subgroup/project-name`), not `:fullpath`.

   Verify success by checking the reviewer state:
   ```bash
   glab api "projects/:fullpath/merge_requests/<MR>/reviewers" | python3 -c "import sys,json; [print(r['user']['username'], r['state']) for r in json.load(sys.stdin)]"
   ```
   Expected: `requested_changes`.

9. **Confirm**: Report which comments were successfully posted and that "Request changes" was set.

## Notes

- Always fetch fresh data with `glab`; never rely on cached or assumed MR state.
- If `glab` is not authenticated, tell the user to run `glab auth login`.
- The MR argument can be a number (e.g. `42`) or a full URL (e.g. `https://git.emico.io/.../-/merge_requests/42`).
- Extract the MR number from a URL if needed before passing it to `glab` commands.

## PHP code style conventions for suggestions

- In PHPDoc blocks, use simple `/** @var array */` — never typed generics like `/** @var array<string, string|null> */`.
