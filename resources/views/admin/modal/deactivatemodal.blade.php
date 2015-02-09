<article class="modal fade" id="deactivate_user_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <section class="modal-dialog">
        <section class="modal-content">
            <header class="modal-header">
                <h4 class="modal-title">Deactivate User</h4>
            </header>
            <section class="modal-body">
                <p class="message">
                    This account can be activated later.<br/>
					Are you sure you wanted to deactivate
                    <strong class="user-name">{{ $user->name }}</strong>?
                </p>
            </section>
            <footer class="modal-footer">
            	<input type="hidden" name="id" value="{{ $user->id }}"/>
                <button type="button" class="btn btn-danger" id="deactivate_user">Deactivate User</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </footer>
        </section>
    </section>
</article>

